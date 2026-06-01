<?php

namespace App\Http\Controllers;

use App\Interfaces\SchoolClassInterface;
use App\Interfaces\SchoolSessionInterface;
use App\Models\Course;
use App\Repositories\RoutineRepository;
use App\Traits\SchoolSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoutineImportController extends Controller
{
    use SchoolSession;

    protected $schoolSessionRepository;
    protected $schoolClassRepository;

    public function __construct(
        SchoolSessionInterface $schoolSessionRepository,
        SchoolClassInterface $schoolClassRepository
    ) {
        $this->schoolSessionRepository = $schoolSessionRepository;
        $this->schoolClassRepository = $schoolClassRepository;
    }

    public function downloadTemplate()
    {
        $filename = "routine_import_template.csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [
            'Day (1-7)',
            'Start Time (HH:MM)',
            'End Time (HH:MM)',
            'Course Name'
        ];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            // Add example rows
            fputcsv($file, ['1', '08:00', '09:00', 'Mathematics']);
            fputcsv($file, ['1', '09:00', '10:00', 'English Language']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'routine_file' => 'required|file|mimes:csv,txt',
            'class_id' => 'required',
            'section_id' => 'required',
        ]);

        $current_school_session_id = $this->getSchoolCurrentSession();

        $file = $request->file('routine_file');
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));

        if (count($data) < 2) {
            return back()->withError('The uploaded file is empty or contains only headers.');
        }

        $header = array_shift($data);

        $expectedColumns = [
            'Day (1-7)' => 'weekday',
            'Start Time (HH:MM)' => 'start',
            'End Time (HH:MM)' => 'end',
            'Course Name' => 'course_name'
        ];

        $mapping = [];
        foreach ($header as $i => $column) {
            $trimmedColumn = trim($column);
            if (isset($expectedColumns[$trimmedColumn])) {
                $mapping[$expectedColumns[$trimmedColumn]] = $i;
            }
        }

        $requiredMapping = ['weekday', 'start', 'end', 'course_name'];
        foreach ($requiredMapping as $field) {
            if (!isset($mapping[$field])) {
                return back()->withError("Required column for '$field' not found in CSV.");
            }
        }

        // Fetch all courses for this class to match by name
        $courses = Course::where('class_id', $request->class_id)
            ->where('session_id', $current_school_session_id)
            ->get();

        try {
            DB::beginTransaction();
            $routineRepository = new RoutineRepository();

            foreach ($data as $index => $line) {
                if (empty(array_filter($line))) {
                    continue;
                }

                $day = trim($line[$mapping['weekday']]);
                $start = trim($line[$mapping['start']]);
                $end = trim($line[$mapping['end']]);
                $courseName = trim($line[$mapping['course_name']]);

                $course = $courses->first(function ($c) use ($courseName) {
                    return strtolower($c->course_name) === strtolower($courseName);
                });

                if (!$course) {
                    throw new \Exception("Course '$courseName' not found for the selected class.");
                }

                $routineData = [
                    'weekday' => $day,
                    'start' => $start,
                    'end' => $end,
                    'class_id' => $request->class_id,
                    'section_id' => $request->section_id,
                    'course_id' => $course->id,
                    'session_id' => $current_school_session_id,
                ];

                $routineRepository->saveRoutine($routineData);
            }
            DB::commit();

            return back()->with('status', 'Routine imported successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Failed to import routine: ' . $e->getMessage());
        }
    }
}
