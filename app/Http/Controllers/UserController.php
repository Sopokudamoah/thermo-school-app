<?php

namespace App\Http\Controllers;

use App\DataTables\StudentsDataTable;
use App\DataTables\TeachersDataTable;
use App\Http\Requests\StudentStoreRequest;
use App\Http\Requests\TeacherStoreRequest;
use App\Interfaces\SchoolClassInterface;
use App\Interfaces\SchoolSessionInterface;
use App\Interfaces\SectionInterface;
use App\Interfaces\UserInterface;
use App\Repositories\PromotionRepository;
use App\Repositories\StudentParentInfoRepository;
use App\Traits\SchoolSession;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use SchoolSession;
    protected $userRepository;
    protected $schoolSessionRepository;
    protected $schoolClassRepository;
    protected $schoolSectionRepository;
    protected $academicSettingRepository;

    public function __construct(UserInterface $userRepository, SchoolSessionInterface $schoolSessionRepository,
    SchoolClassInterface $schoolClassRepository,
        SectionInterface $schoolSectionRepository,
        \App\Interfaces\AcademicSettingInterface $academicSettingRepository
    )
    {
        $this->middleware(['can:view users']);

        $this->userRepository = $userRepository;
        $this->schoolSessionRepository = $schoolSessionRepository;
        $this->schoolClassRepository = $schoolClassRepository;
        $this->schoolSectionRepository = $schoolSectionRepository;
        $this->academicSettingRepository = $academicSettingRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TeacherStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function storeTeacher(TeacherStoreRequest $request)
    {
        try {
            $this->userRepository->createTeacher($request->validated());

            return back()->with('status', 'Teacher creation was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function getStudentList(StudentsDataTable $dataTable, Request $request)
    {
        $current_school_session_id = $this->getSchoolCurrentSession();

        $class_id = $request->query('class_id', 0);
        $section_id = $request->query('section_id', 0);

        try{
            $school_classes = $this->schoolClassRepository->getAllBySession($current_school_session_id);

            $data = [
                'school_classes'    => $school_classes,
                'class_id' => $class_id,
                'section_id' => $section_id,
            ];

            return $dataTable->with([
                'class_id' => $class_id,
                'section_id' => $section_id,
                'session_id' => $current_school_session_id
            ])->render('students.list', $data);
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }


    public function showStudentProfile($id) {
        $student = $this->userRepository->findStudent($id);

        $current_school_session_id = $this->getSchoolCurrentSession();
        $promotionRepository = new PromotionRepository();
        $promotion_info = $promotionRepository->getPromotionInfoById($current_school_session_id, $id);

        $data = [
            'student'           => $student,
            'promotion_info'    => $promotion_info,
        ];

        return view('students.profile', $data);
    }

    public function showTeacherProfile($id) {
        $teacher = $this->userRepository->findTeacher($id);
        $data = [
            'teacher'   => $teacher,
        ];
        return view('teachers.profile', $data);
    }


    public function createStudent() {
        $current_school_session_id = $this->getSchoolCurrentSession();

        $school_classes = $this->schoolClassRepository->getAllBySession($current_school_session_id);

        $data = [
            'current_school_session_id' => $current_school_session_id,
            'school_classes'            => $school_classes,
        ];

        return view('students.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StudentStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function storeStudent(StudentStoreRequest $request)
    {
        try {
            $this->userRepository->createStudent($request->validated());

            return back()->with('status', 'Student creation was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function editStudent($student_id) {
        $student = $this->userRepository->findStudent($student_id);
        $studentParentInfoRepository = new StudentParentInfoRepository();
        $parent_info = $studentParentInfoRepository->getParentInfo($student_id);
        $promotionRepository = new PromotionRepository();
        $current_school_session_id = $this->getSchoolCurrentSession();
        $promotion_info = $promotionRepository->getPromotionInfoById($current_school_session_id, $student_id);

        $data = [
            'student'       => $student,
            'parent_info'   => $parent_info,
            'promotion_info'=> $promotion_info,
        ];
        return view('students.edit', $data);
    }

    public function updateStudent(Request $request) {
        try {
            $this->userRepository->updateStudent($request->toArray());

            return back()->with('status', 'Student update was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function editTeacher($teacher_id) {
        $teacher = $this->userRepository->findTeacher($teacher_id);

        $data = [
            'teacher'   => $teacher,
        ];

        return view('teachers.edit', $data);
    }
    public function updateTeacher(Request $request) {
        try {
            $this->userRepository->updateTeacher($request->toArray());

            return back()->with('status', 'Teacher update was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function getTeacherList(TeachersDataTable $dataTable)
    {
        $current_school_session_id = $this->getSchoolCurrentSession();

        $semesterRepository = new \App\Repositories\SemesterRepository();
        $classRepository = new \App\Repositories\SchoolClassRepository();
        $sectionRepository = new \App\Repositories\SectionRepository();

        $semesters = $semesterRepository->getAll($current_school_session_id);
        $classes = $classRepository->getAllBySession($current_school_session_id);
        $sections = $sectionRepository->getAllBySession($current_school_session_id);

        $academic_setting = $this->academicSettingRepository->getAcademicSetting();

        $data = [
            'semesters'                 => $semesters,
            'classes'                   => $classes,
            'sections'                  => $sections,
            'current_session_id'        => $current_school_session_id,
            'academic_setting' => $academic_setting,
        ];
        return $dataTable->render('teachers.list', $data);
    }

    public function getCoursesByClassAndSemester(Request $request)
    {
        $class_id = $request->query('class_id');
        $semester_id = $request->query('semester_id');

        $courses = \App\Models\Course::where('class_id', $class_id)
            ->where('semester_id', $semester_id)
            ->get();

        return response()->json($courses);
    }
}
