<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExamStoreRequest;
use App\Interfaces\AcademicSettingInterface;
use App\Interfaces\SchoolClassInterface;
use App\Interfaces\SchoolSessionInterface;
use App\Interfaces\SemesterInterface;
use App\Models\Exam;
use App\Repositories\AssignedTeacherRepository;
use App\Repositories\ExamRepository;
use App\Traits\SchoolSession;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    use SchoolSession;

    protected $schoolClassRepository;
    protected $semesterRepository;
    protected $schoolSessionRepository;
    protected $academicSettingRepository;

    public function __construct(
        SchoolSessionInterface $schoolSessionRepository,
        SchoolClassInterface $schoolClassRepository,
        SemesterInterface $semesterRepository,
        AcademicSettingInterface $academicSettingRepository
    ) {
        $this->schoolSessionRepository = $schoolSessionRepository;
        $this->schoolClassRepository = $schoolClassRepository;
        $this->semesterRepository = $semesterRepository;
        $this->academicSettingRepository = $academicSettingRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $class_id = $request->query('class_id', 0);
        $semester_id = $request->query('semester_id', 0);

        $academic_setting = $this->academicSettingRepository->getAcademicSetting();

        if ($semester_id == 0) {
            if ($academic_setting && $academic_setting->active_semester_id) {
                $semester_id = $academic_setting->active_semester_id;
            }
        }

        $current_school_session_id = $this->getSchoolCurrentSession();

        $semesters = $this->semesterRepository->getAll($current_school_session_id);

        $school_classes = $this->schoolClassRepository->getAllBySession($current_school_session_id);

        $examRepository = new ExamRepository();

        $course_id = $request->query('course_id', 0);
        $exams = $examRepository->getAll($current_school_session_id, $semester_id, $class_id, $course_id);

        $assignedTeacherRepository = new AssignedTeacherRepository();

        $teacher_id = (auth()->user()->role == "teacher")?auth()->user()->id : 0;

        $teacherCourses = $assignedTeacherRepository->getTeacherCourses($current_school_session_id, $teacher_id, $semester_id);

        $data = [
            'current_school_session_id' => $current_school_session_id,
            'semesters'                 => $semesters,
            'classes'                   => $school_classes,
            'exams'                     => $exams,
            'teacher_courses'           => $teacherCourses,
            'academic_setting' => $academic_setting,
            'semester_id' => $semester_id,
        ];

        return view('exams.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $current_school_session_id = $this->getSchoolCurrentSession();

        $semesters = $this->semesterRepository->getAll($current_school_session_id);

        if(auth()->user()->role == "teacher") {
            $teacher_id = auth()->user()->id;
            $assigned_classes = $this->schoolClassRepository->getAllBySessionAndTeacher($current_school_session_id, $teacher_id);

            $school_classes = [];
            $i = 0;

            foreach($assigned_classes as $assigned_class) {
                $school_classes[$i] = $assigned_class->schoolClass;
                $i++;
            }
        } else {
            $school_classes = $this->schoolClassRepository->getAllBySession($current_school_session_id);
        }

        $academic_setting = $this->academicSettingRepository->getAcademicSetting();

        $data = [
            'current_school_session_id' => $current_school_session_id,
            'semesters'                 => $semesters,
            'classes'                   => $school_classes,
            'academic_setting' => $academic_setting,
        ];

        return view('exams.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ExamStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExamStoreRequest $request)
    {
        try {
            $examRepository = new ExamRepository();
            $examRepository->create($request->validated());

            return back()->with('status', 'Exam creation was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function show(Exam $exam)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function edit(Exam $exam)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exam $exam)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $examRepository = new ExamRepository();
            $examRepository->delete($request->exam_id);

            return back()->with('status', 'Exam deletion was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
}
