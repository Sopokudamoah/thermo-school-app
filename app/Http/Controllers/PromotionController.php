<?php

namespace App\Http\Controllers;

use App\Interfaces\SchoolClassInterface;
use App\Interfaces\SchoolSessionInterface;
use App\Interfaces\SectionInterface;
use App\Interfaces\UserInterface;
use App\Models\Promotion;
use App\Repositories\PromotionRepository;
use App\Traits\SchoolSession;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PromotionController extends Controller
{
    use SchoolSession;

    protected SchoolSessionInterface $schoolSessionRepository;
    protected UserInterface $userRepository;
    protected SchoolClassInterface $schoolClassRepository;
    protected SectionInterface $schoolSectionRepository;

    /**
    * Create a new Controller instance
     *
    * @param SchoolSessionInterface $schoolSessionRepository
    * @return void
    */
    public function __construct(
        SchoolSessionInterface $schoolSessionRepository,
        UserInterface $userRepository,
        SchoolClassInterface $schoolClassRepository,
        SectionInterface $schoolSectionRepository
    ) {
        $this->schoolSessionRepository = $schoolSessionRepository;
        $this->userRepository = $userRepository;
        $this->schoolClassRepository = $schoolClassRepository;
        $this->schoolSectionRepository = $schoolSectionRepository;
    }
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     *
     * @return Factory|View|Application|Response|\Illuminate\View\View|object
     */
    public function index(Request $request)
    {
        $class_id = $request->query('class_id', 0);

        $promotionRepository = new PromotionRepository();
        $previousSession = $this->schoolSessionRepository->getPreviousSession();

        if(count($previousSession) < 1) {
            return back()->withError('No previous session');
        }

        $previousSessionClasses = $promotionRepository->getClasses($previousSession['id']);

        $previousSessionSections = $promotionRepository->getSections($previousSession['id'], $class_id);

        $current_school_session_id = $this->getSchoolCurrentSession();
        $currentSessionSections = $promotionRepository->getSectionsBySession($current_school_session_id);

        $currentSessionSectionsCounts = $currentSessionSections->count();

        $data = [
            'previousSessionClasses'        => $previousSessionClasses,
            'class_id'                      => $class_id,
            'previousSessionSections'       => $previousSessionSections,
            'currentSessionSectionsCounts'  => $currentSessionSectionsCounts,
            'previousSessionId'             => $previousSession['id'],
            'current_school_session_id' => $current_school_session_id,
        ];

        return view('promotions.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @param  \Illuminate\Http\Request  $request
     *
     * @return Factory|View|Application|Response|\Illuminate\View\View|object
     */
    public function create(Request $request)
    {
        $class_id = $request->query('previous_class_id');
        $section_id = $request->query('previous_section_id');
        $session_id = $request->query('previousSessionId');

        try{

            if($class_id == null || $section_id == null ||$session_id == null) {
                return abort(404);
            }

            $students = $this->userRepository->getAllStudents($session_id, $class_id, $section_id);

            $schoolClass = $this->schoolClassRepository->findById($class_id);
            $section = $this->schoolSectionRepository->findById($section_id);

            $latest_school_session = $this->schoolSessionRepository->getLatestSession();

            $school_classes = $this->schoolClassRepository->getAllBySession($latest_school_session->id);

            $data = [
                'students'      => $students,
                'schoolClass'   => $schoolClass,
                'section'       => $section,
                'school_classes'=> $school_classes,
            ];

            return view('promotions.promote', $data);
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function store(Request $request)
    {
        $id_card_numbers = $request->id_card_number;
        $latest_school_session = $this->schoolSessionRepository->getLatestSession();

        $rows = [];
        $i = 0;
        foreach($id_card_numbers as $student_id => $id_card_number) {
            $row = [
                'student_id'    => $student_id,
                'id_card_number'=> $id_card_number,
                'class_id'      => $request->class_id[$i],
                'section_id'    => $request->section_id[$i],
                'session_id'    => $latest_school_session->id,
            ];
            array_push($rows, $row);
            $i++;
        }

        try {
            $promotionRepository = new PromotionRepository();
            $promotionRepository->massPromotion($rows);

            return back()->with('status', 'Promoting students was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Promotion $promotion
     * @return Response
     */
    public function show(Promotion $promotion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Promotion $promotion
     * @return Response
     */
    public function edit(Promotion $promotion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Promotion $promotion
     * @return Response
     */
    public function update(Request $request, Promotion $promotion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Promotion $promotion
     * @return Response
     */
    public function destroy(Promotion $promotion)
    {
        //
    }
}
