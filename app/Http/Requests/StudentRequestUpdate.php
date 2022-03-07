<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequestUpdate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|min:5',
            'last_name' => 'required|min:5',
            'date_of_birth' => 'required|date',
            'class_id' => 'required',
            'is_active' => 'required|boolean'
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'first_name.required' => 'First Name field is required!',
            'first_name.min' => 'The First Name must be at least 5 characters!',

            'last_name.required'  => 'Last Name field is requried!',
            'last_name.min' => 'The Last Name must be at least 5 characters!',

            'date_of_birth.required'  => 'Date of Birth field is required!',
            'date_of_birth.date' => 'The Date of Birth must be a formated date!',

            'class_id.required' => 'class_id field is required!',

            'is_active.required' => 'Active Section is required!',
            'is_active.boolean' => 'Is active must be a boolean',
        ];
    }
}
