<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PassportLoginRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => 'required',
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
            'email.required'  => 'Email is required!',
            'email.email' => 'The Email must be a valid email address!',
            'password.required' => 'Password is required!',
        ];
    }
}
