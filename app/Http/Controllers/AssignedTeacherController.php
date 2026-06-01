<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherAssignRequest;
use App\Interfaces\SchoolSessionInterface;
use App\Interfaces\SemesterInterface;
use App\Repositories\AssignedTeacherRepository;
use App\Traits\SchoolSession;
use Illuminate\Http\Request;

class AssignedTeacherController extends Controller
{
    use SchoolSession;
    protected $schoolSessionRepository;
    protected $semesterRepository;

    protected $academicSettingRepository;

    /**
    * Create a new Controller instance
     *
    * @param SchoolSessionInterface $schoolSessionRepository
    * @return void
    */
    public function __construct(SchoolSessionInterface $schoolSessionRepository,
        SemesterInterface $semesterRepository,
        \App\Interfaces\AcademicSettingInterface $academicSettingRepository
    ) {
        $this->schoolSessionRepository = $schoolSessionRepository;
        $this->semesterRepository = $semesterRepository;
        $this->academicSettingRepository = $academicSettingRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @param  CourseStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function getTeacherCourses(Request $request)
    {
        $teacher_id = $request->query('teacher_id');
        $semester_id = $request->query('semester_id');

        if($teacher_id == null) {
            abort(404);
        }

        $current_school_session_id = $this->getSchoolCurrentSession();

        $semesters = $this->semesterRepository->getAll($current_school_session_id);

        $assignedTeacherRepository = new AssignedTeacherRepository();

        if ($semester_id == null) {
            $academic_setting = $this->academicSettingRepository->getAcademicSetting();
            if ($academic_setting && $academic_setting->active_semester_id) {
                $semester_id = $academic_setting->active_semester_id;
            }
        }

        if($semester_id == null) {
            $courses = [];
        } else {
            $courses = $assignedTeacherRepository->getTeacherCourses($current_school_session_id, $teacher_id, $semester_id);
        }

        $data = [
            'courses'               => $courses,
            'semesters'             => $semesters,
            'selected_semester_id'  => $semester_id,
        ];

        return view('courses.teacher', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TeacherAssignRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeacherAssignRequest $request)
    {
        try {
            $assignedTeacherRepository = new AssignedTeacherRepository();
            $assignedTeacherRepository->assign($request->validated());

            return back()->with('status', 'Assigning teacher was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
}
