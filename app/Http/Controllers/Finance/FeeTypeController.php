<?php

namespace App\Http\Controllers\Finance;

use App\DataTables\Finance\FeeTypeDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\FeeTypeRequest;
use App\Interfaces\Finance\FeeTypeInterface;

class FeeTypeController extends Controller
{
    protected $feeTypeRepository;

    public function __construct(FeeTypeInterface $feeTypeRepository)
    {
        $this->middleware(['can:finance.view']);
        $this->feeTypeRepository = $feeTypeRepository;
    }

    public function index(FeeTypeDataTable $dataTable)
    {
        return $dataTable->render('finance.fee-types.index');
    }

    public function create()
    {
        return view('finance.fee-types.create');
    }

    public function store(FeeTypeRequest $request)
    {
        try {
            $this->feeTypeRepository->create($request->validated());
            return back()->with('status', 'Fee type created successfully.');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function edit($id)
    {
        $fee_type = $this->feeTypeRepository->findById($id);
        return view('finance.fee-types.edit', compact('fee_type'));
    }

    public function update(FeeTypeRequest $request, $id)
    {
        try {
            $this->feeTypeRepository->update($id, $request->validated());
            return back()->with('status', 'Fee type updated successfully.');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
}
