<?php

namespace App\Http\Controllers\Finance;

use App\DataTables\Finance\FeeStructureDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\FeeStructureRequest;
use App\Interfaces\Finance\FeeStructureInterface;
use App\Interfaces\Finance\FeeTypeInterface;
use App\Models\SchoolClass;
use App\Models\SchoolSession;
use App\Models\Section;
use App\Models\Semester;

class FeeStructureController extends Controller
{
    protected $feeStructureRepository;
    protected $feeTypeRepository;

    public function __construct(
        FeeStructureInterface $feeStructureRepository,
        FeeTypeInterface $feeTypeRepository
    ) {
        $this->middleware(['can:finance.view']);
        $this->feeStructureRepository = $feeStructureRepository;
        $this->feeTypeRepository = $feeTypeRepository;
    }

    public function index(FeeStructureDataTable $dataTable)
    {
        return $dataTable->render('finance.fee-structures.index');
    }

    public function create()
    {
        $fee_types = $this->feeTypeRepository->getActive();
        $sessions = SchoolSession::orderByDesc('id')->get();
        $classes = SchoolClass::orderBy('class_name')->get();
        $sections = Section::orderBy('section_name')->get();
        $semesters = Semester::orderBy('semester_name')->get();

        return view(
            'finance.fee-structures.create',
            compact('fee_types', 'sessions', 'classes', 'sections', 'semesters')
        );
    }

    public function store(FeeStructureRequest $request)
    {
        try {
            $data = $request->validated();
            $items = $data['items'];
            unset($data['items']);

            $structure = $this->feeStructureRepository->create($data);
            $this->feeStructureRepository->syncItems($structure->id, $items);

            return redirect()->route('finance.fee-structures.index')->with(
                'status',
                'Fee structure created successfully.'
            );
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function show($id)
    {
        $fee_structure = $this->feeStructureRepository->findById($id);
        return view('finance.fee-structures.show', compact('fee_structure'));
    }

    public function edit($id)
    {
        $fee_structure = $this->feeStructureRepository->findById($id);
        $fee_types = $this->feeTypeRepository->getActive();
        $sessions = SchoolSession::orderByDesc('id')->get();
        $classes = SchoolClass::orderBy('class_name')->get();
        $sections = Section::orderBy('section_name')->get();
        $semesters = Semester::orderBy('semester_name')->get();

        return view(
            'finance.fee-structures.edit',
            compact('fee_structure', 'fee_types', 'sessions', 'classes', 'sections', 'semesters')
        );
    }

    public function update(FeeStructureRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $items = $data['items'];
            unset($data['items']);

            $this->feeStructureRepository->update($id, $data);
            $this->feeStructureRepository->syncItems($id, $items);

            return redirect()->route('finance.fee-structures.index')->with(
                'status',
                'Fee structure updated successfully.'
            );
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
}
