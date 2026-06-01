<?php

namespace App\Repositories;

use App\Interfaces\ExamInterface;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Semester;

class ExamRepository implements ExamInterface {
    public function create($request) {
        try {
            Exam::create($request);
        } catch (\Exception $e) {
            throw new \Exception('Failed to create exam. '.$e->getMessage());
        }
    }

    public function delete($id) {
        try {
            Exam::destroy($id);
        } catch (\Exception $e) {
            throw new \Exception('Failed to delete exam. '.$e->getMessage());
        }
    }

    public function getAll($session_id, $semester_id, $class_id, $course_id = 0)
    {
        if($semester_id == 0 || $class_id == 0) {
            $semester_id = Semester::where('session_id', $session_id)
            ->first()->id;
            $class_id = SchoolClass::where('session_id', $session_id)
                                    ->first()->id;
        }

        $query = Exam::with('course')->where('session_id', $session_id)
                    ->where('semester_id', $semester_id)
            ->where('class_id', $class_id);

        if ($course_id > 0) {
            $query->where('course_id', $course_id);
        }

        if (auth()->check() && auth()->user()->isTeacher()) {
            $query->whereIn('course_id', function ($query) {
                $query->select('course_id')
                    ->from('assigned_teachers')
                    ->where('teacher_id', auth()->id());
            });
        }

        return $query->get();
    }
}
