<?php

namespace App\Http\Controllers\Finance;

use App\DataTables\Finance\DiscountDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\DiscountRequest;
use App\Interfaces\Finance\DiscountInterface;

class DiscountController extends Controller
{
    protected $discountRepository;

    public function __construct(DiscountInterface $discountRepository)
    {
        $this->middleware(['can:finance.view']);
        $this->discountRepository = $discountRepository;
    }

    public function index(DiscountDataTable $dataTable)
    {
        return $dataTable->render('finance.discounts.index');
    }

    public function create()
    {
        return view('finance.discounts.create');
    }

    public function store(DiscountRequest $request)
    {
        try {
            $this->discountRepository->create($request->validated());
            return redirect()->route('finance.discounts.index')->with('status', 'Discount created successfully.');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function edit($id)
    {
        $discount = $this->discountRepository->findById($id);
        return view('finance.discounts.edit', compact('discount'));
    }

    public function update(DiscountRequest $request, $id)
    {
        try {
            $this->discountRepository->update($id, $request->validated());
            return redirect()->route('finance.discounts.index')->with('status', 'Discount updated successfully.');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
}
