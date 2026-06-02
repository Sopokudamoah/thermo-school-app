<?php

namespace App\Interfaces\Finance;

interface ScholarshipInterface
{
    public function getAll();

    public function getActive();

    public function findById($id);

    public function create(array $data);

    public function update($id, array $data);

    public function assignToStudent(array $data);

    public function getStudentScholarships($student_id);

    public function revokeStudentScholarship($student_scholarship_id);
}
