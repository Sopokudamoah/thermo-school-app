<?php

namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\Models\AcademicSetting;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use App\Traits\Base64ToFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserInterface {
    use Base64ToFile;

    /**
     * @param mixed $request
     * @return string
    */
    public function createTeacher($request) {
        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'first_name'    => $request['first_name'],
                    'last_name'     => $request['last_name'],
                    'email'         => $request['email'],
                    'gender'        => $request['gender'],
                    'nationality'   => $request['nationality'],
                    'phone'         => $request['phone'],
                    'address'       => $request['address'],
                    'address2'      => $request['address2'],
                    'city'          => $request['city'],
                    'zip'           => $request['zip'],
                    'photo'         => (!empty($request['photo']))?$this->convert($request['photo']):null,
                    'role'          => 'teacher',
                    'password'      => Hash::make($request['password']),
                ]);
                $user->givePermissionTo(
                    'create exams',
                    'view exams',
                    'create exams rule',
                    'view exams rule',
                    'edit exams rule',
                    'delete exams rule',
                    'take attendances',
                    'view attendances',
                    'create assignments',
                    'view assignments',
                    'save marks',
                    'view users',
                    'view routines',
                    'view syllabi',
                    'view events',
                    'view notices',
                );
            });
        } catch (\Exception $e) {
            throw new \Exception('Failed to create Teacher. '.$e->getMessage());
        }
    }

    /**
     * @param mixed $request
     * @return string
    */
    public function createStudent($request) {
        try {
            DB::transaction(function () use ($request) {
                $student = Student::create([
                    'first_name'    => $request['first_name'],
                    'last_name'     => $request['last_name'],
                    'email'         => $request['email'],
                    'gender'        => $request['gender'],
                    'nationality'   => $request['nationality'],
                    'phone_number' => $request['phone'],
                    'address'       => $request['address'],
                    'address2'      => $request['address2'],
                    'city'          => $request['city'],
                    'zip'           => $request['zip'],
                    'photo'         => (!empty($request['photo']))?$this->convert($request['photo']):null,
                    'birthday'      => $request['birthday'],
                    'religion'      => $request['religion'],
                    'blood_type'    => $request['blood_type'],
                ]);

                // Store Parents' information
                $studentParentInfoRepository = new StudentParentInfoRepository();
                $studentParentInfoRepository->store($request, $student->id);

                // Store Academic information
                $studentAcademicInfoRepository = new StudentAcademicInfoRepository();
                $studentAcademicInfoRepository->store($request, $student->id);

                // Assign student to a Class and a Section
                $promotionRepository = new PromotionRepository();
                $promotionRepository->assignClassSection($request, $student->id);
            });
        } catch (\Exception $e) {
            throw new \Exception('Failed to create Student. '.$e->getMessage());
        }
    }

    public function updateStudent($request) {
        try {
            DB::transaction(function () use ($request) {
                Student::where('id', $request['student_id'])->update([
                    'first_name'    => $request['first_name'],
                    'last_name'     => $request['last_name'],
                    'email'         => $request['email'],
                    'gender'        => $request['gender'],
                    'nationality'   => $request['nationality'],
                    'phone_number' => $request['phone'],
                    'address'       => $request['address'],
                    'address2'      => $request['address2'],
                    'city'          => $request['city'],
                    'zip'           => $request['zip'],
                    'birthday'      => $request['birthday'],
                    'religion'      => $request['religion'],
                    'blood_type'    => $request['blood_type'],
                ]);

                // Update Parents' information
                $studentParentInfoRepository = new StudentParentInfoRepository();
                $studentParentInfoRepository->update($request, $request['student_id']);

                // Update Student's ID card number
                $promotionRepository = new PromotionRepository();
                $promotionRepository->update($request, $request['student_id']);
            });
        } catch (\Exception $e) {
            throw new \Exception('Failed to update Student. '.$e->getMessage());
        }
    }

    public function updateTeacher($request) {
        try {
            DB::transaction(function () use ($request) {
                User::where('id', $request['teacher_id'])->update([
                    'first_name'    => $request['first_name'],
                    'last_name'     => $request['last_name'],
                    'email'         => $request['email'],
                    'gender'        => $request['gender'],
                    'nationality'   => $request['nationality'],
                    'phone'         => $request['phone'],
                    'address'       => $request['address'],
                    'address2'      => $request['address2'],
                    'city'          => $request['city'],
                    'zip'           => $request['zip'],
                ]);
            });
        } catch (\Exception $e) {
            throw new \Exception('Failed to update Teacher. '.$e->getMessage());
        }
    }

    public function getAllStudents($session_id, $class_id, $section_id) {
        if($class_id == 0 || $section_id == 0) {
            $schoolClass = SchoolClass::where('session_id', $session_id)
                                    ->first();
            $section = Section::where('session_id', $session_id)
                                    ->first();
            if($schoolClass == null || $section == null){
                throw new \Exception('There is no class and section');
            } else {
                $class_id = $schoolClass->id;
                $section_id = $section->id;
            }

        }
        try {
            $promotionRepository = new PromotionRepository();
            return $promotionRepository->getAll($session_id, $class_id, $section_id);
        } catch (\Exception $e) {
            throw new \Exception('Failed to get all Students. '.$e->getMessage());
        }
    }

    public function getAllStudentsBySession($session_id) {
        $promotionRepository = new PromotionRepository();
        return $promotionRepository->getAllStudentsBySession($session_id);
    }

    public function getAllStudentsBySessionCount($session_id) {
        $promotionRepository = new PromotionRepository();
        return $promotionRepository->getAllStudentsBySessionCount($session_id);
    }

    public function findStudent($id) {
        try {
            return Student::with('parent_info', 'academic_info')->where('id', $id)->first();
        } catch (\Exception $e) {
            throw new \Exception('Failed to get Student. '.$e->getMessage());
        }
    }

    public function findTeacher($id) {
        try {
            $academic_setting = AcademicSetting::first();
            $semester_id = $academic_setting->active_semester_id ?? null;

            return User::where('id', $id)->where('role', 'teacher')
                ->with([
                    'assigned_classes' => function ($query) use ($semester_id) {
                        if ($semester_id) {
                            $query->where('semester_id', $semester_id);
                        }
                        $query->with(['schoolClass', 'section', 'course', 'schoolSession']);
                }])->first();
        } catch (\Exception $e) {
            throw new \Exception('Failed to get Teacher. '.$e->getMessage());
        }
    }

    public function getAllTeachers($session_id = null) {
        try {
            if ($session_id) {
                return User::where('role', 'teacher')
                    ->with(['assigned_classes' => function($query) use ($session_id) {
                        $query->where('session_id', $session_id)->with('schoolClass');
                    }])->get();
            }
            return User::where('role', 'teacher')->get();
        } catch (\Exception $e) {
            throw new \Exception('Failed to get all Teachers. '.$e->getMessage());
        }
    }

    public function changePassword($new_password) {
        try {
            return User::where('id', auth()->user()->id)->update([
                'password'  => Hash::make($new_password)
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Failed to change password. '.$e->getMessage());
        }
    }
}
