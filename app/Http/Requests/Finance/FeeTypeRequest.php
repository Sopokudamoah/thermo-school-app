<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class FeeTypeRequest extends FormRequest
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
            'code' => 'required|string|max:50|unique:finance_fee_types,code' . ($id ? ",$id" : ''),
            'description' => 'nullable|string',
            'recurring' => 'boolean',
            'active' => 'boolean',
        ];
    }
}
