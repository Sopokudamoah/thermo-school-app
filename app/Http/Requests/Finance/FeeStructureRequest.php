<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class FeeStructureRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('finance.view');
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'session_id' => 'required|integer',
            'semester_id' => 'nullable|integer',
            'class_id' => 'required|integer',
            'section_id' => 'nullable|integer',
            'active' => 'boolean',
            'items' => 'required|array|min:1',
            'items.*.fee_type_id' => 'required|integer|exists:finance_fee_types,id',
            'items.*.amount' => 'required|numeric|min:0',
        ];
    }
}
