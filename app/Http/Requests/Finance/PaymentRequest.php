<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('finance.payment.create');
    }

    public function rules()
    {
        return [
            'student_id' => 'required|integer|exists:students,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|string|in:cash,bank_transfer,card,online_gateway,mobile_money',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'allocations' => 'required|array|min:1',
            'allocations.*.invoice_id' => 'required|integer|exists:finance_invoices,id',
            'allocations.*.amount' => 'required|numeric|min:0.01',
        ];
    }
}
