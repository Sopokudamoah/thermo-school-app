<?php

namespace App\Http\Controllers\Finance;

use App\DataTables\Finance\VendorDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\VendorRequest;
use App\Interfaces\Finance\VendorInterface;

class VendorController extends Controller
{
    protected $vendorRepository;

    public function __construct(VendorInterface $vendorRepository)
    {
        $this->middleware(['can:finance.view']);
        $this->vendorRepository = $vendorRepository;
    }

    public function index(VendorDataTable $dataTable)
    {
        return $dataTable->render('finance.vendors.index');
    }

    public function create()
    {
        return view('finance.vendors.create');
    }

    public function store(VendorRequest $request)
    {
        try {
            $this->vendorRepository->create($request->validated());
            return redirect()->route('finance.vendors.index')->with('status', 'Vendor created successfully.');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function edit($id)
    {
        $vendor = $this->vendorRepository->findById($id);
        return view('finance.vendors.edit', compact('vendor'));
    }

    public function update(VendorRequest $request, $id)
    {
        try {
            $this->vendorRepository->update($id, $request->validated());
            return redirect()->route('finance.vendors.index')->with('status', 'Vendor updated successfully.');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
}
