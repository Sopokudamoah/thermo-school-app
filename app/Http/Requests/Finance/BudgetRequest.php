<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class BudgetRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('finance.budget.manage');
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'year' => 'required|integer|min:2000|max:2100',
            'total_allocated' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'active' => 'boolean',
        ];
    }
}
