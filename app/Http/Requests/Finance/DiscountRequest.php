<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class DiscountRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('finance.view');
    }

    public function rules()
    {
        $id = $this->route('id');

        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:finance_discounts,code' . ($id ? ",$id" : ''),
            'description' => 'nullable|string',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'active' => 'boolean',
        ];
    }
}
