<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class ScholarshipRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('finance.view');
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'active' => 'boolean',
        ];
    }
}
