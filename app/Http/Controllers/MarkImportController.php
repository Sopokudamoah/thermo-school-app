<?php

namespace App\Http\Controllers;

use App\Interfaces\SchoolSessionInterface;
use App\Models\Course;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Repositories\ExamRepository;
use App\Repositories\MarkRepository;
use App\Traits\AssignedTeacherCheck;
use App\Traits\SchoolSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarkImportController extends Controller
{
    use SchoolSession, AssignedTeacherCheck;

    protected $schoolSessionRepository;

    public function __construct(SchoolSessionInterface $schoolSessionRepository)
    {
        $this->schoolSessionRepository = $schoolSessionRepository;
    }

    public function downloadTemplate(Request $request)
    {
        $current_school_session_id = $this->getSchoolCurrentSession();
        $this->checkIfLoggedInUserIsAssignedTeacher($request, $current_school_session_id);

        $class = SchoolClass::findOrFail($request->class_id);
        $section = Section::findOrFail($request->section_id);
        $course = Course::findOrFail($request->course_id);
        $semester_id = $request->semester_id;
        $class_id = $request->class_id;

        $examRepository = new ExamRepository();
        $exams = $examRepository->getAll($current_school_session_id, $semester_id, $class_id, $request->course_id);

        $students = Student::with([
            'marks' => function ($query) use ($exams) {
                $query->whereIn('exam_id', $exams->pluck('id'));
            }
        ])->whereHas('promotions', function ($query) use ($request, $current_school_session_id) {
            $query->where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->where('session_id', $current_school_session_id);
        })->get();

        $filename = "marks_template_{$class->class_name}_{$section->section_name}_{$course->course_name}.csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Student ID', 'Student Name'];
        foreach ($exams as $exam) {
            $columns[] = "{$exam->exam_name} (ID:{$exam->id})";
        }

        $callback = function() use($students, $columns, $exams) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($students as $student) {
                $row = [$student->id, "{$student->first_name} {$student->last_name}"];
                foreach ($exams as $exam) {
                    $mark = $student->marks->where('exam_id', $exam->id)->first();
                    $row[] = $mark ? $mark->marks : '';
                }
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'marks_file' => 'required|file|mimes:csv,txt',
            'class_id'   => 'required',
            'section_id' => 'required',
            'course_id'  => 'required',
            'semester_id'=> 'required',
        ]);

        $current_school_session_id = $this->getSchoolCurrentSession();
        $this->checkIfLoggedInUserIsAssignedTeacher($request, $current_school_session_id);

        $file = $request->file('marks_file');
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));

        if (count($data) < 1) {
            return back()->withError('The uploaded file is empty.');
        }

        $header = array_shift($data);
        if (!$header) {
            return back()->withError('Invalid CSV file format.');
        }

        $examIds = [];
        foreach ($header as $i => $column) {
            if (preg_match('/\(ID:(\d+)\)/', $column, $matches)) {
                $examIds[$i] = $matches[1];
            }
        }

        if (empty($examIds)) {
            return back()->withError('No valid exam IDs found in the header.');
        }

        $rows = [];
        foreach ($data as $line) {
            if (empty($line) || count($line) < 2) {
                continue;
            }
            $studentId = $line[0];
            foreach ($examIds as $i => $examId) {
                if (isset($line[$i]) && $line[$i] !== '') {
                    $rows[] = [
                        'exam_id' => $examId,
                        'student_id' => $studentId,
                        'session_id' => $current_school_session_id,
                        'class_id'   => $request->class_id,
                        'section_id' => $request->section_id,
                        'course_id'  => $request->course_id,
                        'marks'      => $line[$i]
                    ];
                }
            }
        }

        try {
            DB::beginTransaction();
            $markRepository = new MarkRepository();
            $markRepository->create($rows);
            DB::commit();

            return back()->with('status', 'Marks imported successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Failed to import marks: ' . $e->getMessage());
        }
    }
}
