<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('finance.invoice.create');
    }

    public function rules()
    {
        return [
            'student_id' => 'required|integer|exists:students,id',
            'session_id' => 'required|integer',
            'semester_id' => 'nullable|integer',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.fee_type_id' => 'required|integer|exists:finance_fee_types,id',
            'items.*.description' => 'required|string|max:255',
            'items.*.amount' => 'required|numeric|min:0',
        ];
    }
}
