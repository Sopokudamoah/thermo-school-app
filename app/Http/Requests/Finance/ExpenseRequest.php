<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('finance.view');
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:finance_expense_categories,id',
            'vendor_id' => 'nullable|integer|exists:finance_vendors,id',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'description' => 'nullable|string',
            'receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }
}
