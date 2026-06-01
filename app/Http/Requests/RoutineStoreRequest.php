<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoutineStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('create routines');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start'                 => 'required|date_format:H:i',
            'end'                   => 'required|date_format:H:i|after:start',
            'weekday'               => 'required|integer|between:1,7',
            'class_id'              => 'required|integer',
            'section_id'            => 'required|integer',
            'course_id'             => 'required|integer',
            'session_id'            => 'required|integer',
        ];
    }
}
