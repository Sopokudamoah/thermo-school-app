<?php

namespace App\Http\Controllers;

use App\Interfaces\SchoolSessionInterface;
use App\Repositories\UserRepository;
use App\Traits\SchoolSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentImportController extends Controller
{
    use SchoolSession;

    protected $schoolSessionRepository;
    protected $userRepository;

    public function __construct(SchoolSessionInterface $schoolSessionRepository, UserRepository $userRepository)
    {
        $this->schoolSessionRepository = $schoolSessionRepository;
        $this->userRepository = $userRepository;
    }

    public function downloadTemplate()
    {
        $filename = "student_import_template.csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [
            'First Name',
            'Last Name',
            'Email',
            'Gender',
            'Phone',
            'Birthday (YYYY-MM-DD)',
            'ID Card Number',
            'Nationality',
            'Address',
            'City',
            'Religion',
            'Blood Type',
            'Father Name',
            'Father Phone',
            'Mother Name',
            'Mother Phone',
            'Parent Address'
        ];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'student_file' => 'required|file|mimes:csv,txt',
            'class_id' => 'required',
            'section_id' => 'required',
        ]);

        $current_school_session_id = $this->getSchoolCurrentSession();

        $file = $request->file('student_file');
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));

        if (count($data) < 2) {
            return back()->withError('The uploaded file is empty or contains only headers.');
        }

        $header = array_shift($data);

        // Define expected columns and their mapping
        $expectedColumns = [
            'First Name' => 'first_name',
            'Last Name' => 'last_name',
            'Email' => 'email',
            'Gender' => 'gender',
            'Phone' => 'phone',
            'Birthday (YYYY-MM-DD)' => 'birthday',
            'ID Card Number' => 'id_card_number',
            'Nationality' => 'nationality',
            'Address' => 'address',
            'City' => 'city',
            'Religion' => 'religion',
            'Blood Type' => 'blood_type',
            'Father Name' => 'father_name',
            'Father Phone' => 'father_phone',
            'Mother Name' => 'mother_name',
            'Mother Phone' => 'mother_phone',
            'Parent Address' => 'parent_address'
        ];

        $mapping = [];
        foreach ($header as $i => $column) {
            $trimmedColumn = trim($column);
            if (isset($expectedColumns[$trimmedColumn])) {
                $mapping[$expectedColumns[$trimmedColumn]] = $i;
            }
        }

        // Basic validation of required fields in CSV
        $requiredMapping = ['first_name', 'last_name', 'gender', 'id_card_number'];
        foreach ($requiredMapping as $field) {
            if (!isset($mapping[$field])) {
                return back()->withError("Required column '$field' not found in CSV.");
            }
        }

        try {
            DB::beginTransaction();
            foreach ($data as $index => $line) {
                if (empty(array_filter($line))) {
                    continue;
                }

                $studentData = [
                    'class_id' => $request->class_id,
                    'section_id' => $request->section_id,
                    'session_id' => $current_school_session_id,
                ];

                foreach ($expectedColumns as $key => $field) {
                    if (isset($mapping[$field])) {
                        $studentData[$field] = $line[$mapping[$field]] ?? null;
                    }
                }

                // Call repository to create student
                $this->userRepository->createStudent($studentData);
            }
            DB::commit();

            return back()->with('status', 'Students imported successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Failed to import students: ' . $e->getMessage());
        }
    }
}
