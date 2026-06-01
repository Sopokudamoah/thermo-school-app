<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('create users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'        => 'required|string',
            'last_name'         => 'required|string',
            'email' => 'nullable|string|email|max:255|unique:students,email',
            'gender'            => 'required|string',
            'nationality' => 'nullable|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'address2'          => 'nullable|string',
            'city' => 'nullable|string',
            'zip' => 'nullable|string',
            'photo'             => 'nullable|string',
            'birthday' => 'nullable|date',
            'religion' => 'nullable|string',
            'blood_type' => 'nullable|string',
            'password' => 'nullable|string|min:8',

            // Parents' information
            'father_name' => 'nullable|string',
            'father_phone' => 'nullable|string',
            'mother_name' => 'nullable|string',
            'mother_phone' => 'nullable|string',
            'parent_address' => 'nullable|string',

            // Academic information
            'class_id'          => 'required',
            'section_id'        => 'required',
            'board_reg_no' => 'nullable|string',
            'session_id'        => 'required',
            'id_card_number'    => 'required',
        ];
    }
}
