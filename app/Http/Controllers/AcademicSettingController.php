<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceTypeUpdateRequest;
use App\Interfaces\AcademicSettingInterface;
use App\Interfaces\CourseInterface;
use App\Interfaces\SchoolClassInterface;
use App\Interfaces\SchoolSessionInterface;
use App\Interfaces\SectionInterface;
use App\Interfaces\SemesterInterface;
use App\Interfaces\UserInterface;
use App\Traits\SchoolSession;
use Illuminate\Http\Request;

class AcademicSettingController extends Controller
{
    use SchoolSession;
    protected $academicSettingRepository;
    protected $schoolSessionRepository;
    protected $schoolClassRepository;
    protected $schoolSectionRepository;
    protected $userRepository;
    protected $courseRepository;
    protected $semesterRepository;

    public function __construct(
        AcademicSettingInterface $academicSettingRepository,
        SchoolSessionInterface $schoolSessionRepository,
        SchoolClassInterface $schoolClassRepository,
        SectionInterface $schoolSectionRepository,
        UserInterface $userRepository,
        CourseInterface $courseRepository,
        SemesterInterface $semesterRepository
    ) {
        $this->middleware(['can:view academic settings']);

        $this->academicSettingRepository = $academicSettingRepository;
        $this->schoolSessionRepository = $schoolSessionRepository;
        $this->schoolClassRepository = $schoolClassRepository;
        $this->schoolSectionRepository = $schoolSectionRepository;
        $this->userRepository = $userRepository;
        $this->courseRepository = $courseRepository;
        $this->semesterRepository = $semesterRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $current_school_session_id = $this->getSchoolCurrentSession();

        $latest_school_session = $this->schoolSessionRepository->getLatestSession();

        $academic_setting = $this->academicSettingRepository->getAcademicSetting();

        $school_sessions = $this->schoolSessionRepository->getAll();

        $school_classes = $this->schoolClassRepository->getAllBySession($current_school_session_id);

        $school_sections = $this->schoolSectionRepository->getAllBySession($current_school_session_id);

        $teachers = $this->userRepository->getAllTeachers();

        $courses = $this->courseRepository->getAll($current_school_session_id);

        $semesters = $this->semesterRepository->getAll($current_school_session_id);

        $data = [
            'current_school_session_id' => $current_school_session_id,
            'latest_school_session_id'  => $latest_school_session->id,
            'academic_setting'          => $academic_setting,
            'school_sessions'           => $school_sessions,
            'school_classes'            => $school_classes,
            'school_sections'           => $school_sections,
            'teachers'                  => $teachers,
            'courses'                   => $courses,
            'semesters'                 => $semesters,
        ];

        return view('academics.settings', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AttendanceTypeUpdateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function updateAttendanceType(AttendanceTypeUpdateRequest $request)
    {
        try {
            $this->academicSettingRepository->updateAttendanceType($request->validated());

            return back()->with('status', 'Attendance type update was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function updateFinalMarksSubmissionStatus(Request $request) {
        try {
            $this->academicSettingRepository->updateFinalMarksSubmissionStatus($request);

            return back()->with('status', 'Final marks submission status update was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function updateActiveSemester(Request $request)
    {
        try {
            $this->academicSettingRepository->updateActiveSemester($request);

            return back()->with('status', 'Active semester update was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function updateActiveSession(Request $request)
    {
        try {
            $this->academicSettingRepository->updateActiveSession($request);

            return back()->with('status', 'Active session update was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function updateGeneralSettings(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
            'school_address' => 'nullable|string',
            'school_phone' => 'nullable|string|max:20',
            'school_email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'currency_symbol' => 'required|string|max:10',
            'currency_code' => 'required|string|max:10',
        ]);

        try {
            $data = $request->except(['_token', 'logo']);

            if ($request->hasFile('logo')) {
                $imageName = time() . '.' . $request->logo->extension();
                $request->logo->move(public_path('images'), $imageName);
                $data['logo'] = 'images/' . $imageName;
            }

            $this->academicSettingRepository->updateGeneralSettings($data);

            return back()->with('status', 'General settings updated successfully!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
}
