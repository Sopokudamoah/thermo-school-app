<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('finance.view');
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'active' => 'boolean',
        ];
    }
}
