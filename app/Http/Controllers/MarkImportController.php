<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Course;
use App\Models\GradingSystem;
use App\Models\GradeRule;
use App\Models\Mark;
use App\Repositories\MarkRepository;
use App\Repositories\GradingSystemRepository;
use App\Repositories\GradeRuleRepository;
use App\Traits\SchoolSession;
use App\Traits\AssignedTeacherCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class MarkImportController extends Controller
{
    use SchoolSession, AssignedTeacherCheck;

    public function downloadTemplate(Request $request)
    {
        $current_school_session_id = $this->getSchoolCurrentSession();
        $this->checkIfLoggedInUserIsAssignedTeacher($request, $current_school_session_id);

        $class = SchoolClass::findOrFail($request->class_id);
        $section = Section::findOrFail($request->section_id);
        $course = Course::findOrFail($request->course_id);
        $semester_id = $request->semester_id;

        $exams = Exam::where('semester_id', $semester_id)
            ->where('class_id', $request->class_id)
            ->where('course_id', $request->course_id)
            ->get();

        $students = User::whereHas('promotions', function ($query) use ($request, $current_school_session_id) {
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
                    $row[] = ''; // Empty for teacher to fill
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
        $examIds = [];
        for ($i = 2; $i < count($header); $i++) {
            if (preg_match('/\(ID:(\d+)\)/', $header[$i], $matches)) {
                $examIds[$i] = $matches[1];
            }
        }

        if (empty($examIds)) {
            return back()->withError('No valid exam IDs found in the header.');
        }

        $rows = [];
        foreach ($data as $line) {
            $studentId = $line[0];
            for ($i = 2; $i < count($line); $i++) {
                if (isset($examIds[$i]) && $line[$i] !== '') {
                    $rows[] = [
                        'exam_id'    => $examIds[$i],
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
