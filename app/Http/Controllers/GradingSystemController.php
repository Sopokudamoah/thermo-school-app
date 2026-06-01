<?php

namespace App\Http\Controllers;

use App\Http\Requests\GradingSystemStoreRequest;
use App\Interfaces\AcademicSettingInterface;
use App\Interfaces\SchoolClassInterface;
use App\Interfaces\SchoolSessionInterface;
use App\Interfaces\SemesterInterface;
use App\Models\GradingSystem;
use App\Repositories\GradingSystemRepository;
use App\Traits\SchoolSession;
use Illuminate\Http\Request;

class GradingSystemController extends Controller
{
    use SchoolSession;

    protected $schoolClassRepository;
    protected $schoolSessionRepository;
    protected $semesterRepository;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gradingSystemRepository = new GradingSystemRepository();
        $current_school_session_id = $this->getSchoolCurrentSession();
        $gradingSystems = $gradingSystemRepository->getAll($current_school_session_id);

        $data = [
            'gradingSystems'            => $gradingSystems,
            'current_school_session_id' => $current_school_session_id,
        ];

        return view('exams.grade.view', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $current_school_session_id = $this->getSchoolCurrentSession();
        $school_classes = $this->schoolClassRepository->getAllBySession($current_school_session_id);
        $semesters = $this->semesterRepository->getAll($current_school_session_id);
        $academic_setting = $this->academicSettingRepository->getAcademicSetting();

        $data = [
            'current_school_session_id' => $current_school_session_id,
            'school_classes'            => $school_classes,
            'semesters'                 => $semesters,
            'academic_setting' => $academic_setting,
        ];

        return view('exams.grade.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  GradingSystemStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GradingSystemStoreRequest $request)
    {
        try {
            $gradingSystemRepository = new GradingSystemRepository();
            $gradingSystemRepository->store($request->validated());

            return back()->with('status', 'Creating grading system was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GradingSystem  $gradingSystem
     * @return \Illuminate\Http\Response
     */
    public function show(GradingSystem $gradingSystem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GradingSystem  $gradingSystem
     * @return \Illuminate\Http\Response
     */
    public function edit(GradingSystem $gradingSystem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GradingSystem  $gradingSystem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GradingSystem $gradingSystem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GradingSystem  $gradingSystem
     * @return \Illuminate\Http\Response
     */
    public function destroy(GradingSystem $gradingSystem)
    {
        //
    }
}
