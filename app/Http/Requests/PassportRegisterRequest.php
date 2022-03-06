<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PassportRegisterRequest extends FormRequest
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
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
            'password_confirmation' => 'required|same:password'
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
            'first_name.required' => 'First Name is required!',
            'first_name.min' => 'The First Name must be at least 5 characters!',

            'last_name.required'  => 'Last Name is requried!',
            'last_name.min' => 'The Last Name must be at least 5 characters!',

            'email.required'  => 'Email is required!',
            'email.email' => 'The Email must be a valid email address!',
            'email.unique' => 'The Email has already been taken!',

            'password.required' => 'Password is required!',
            'password.min' => 'The Password must be at least 5 characters!',

            'password_confirmation.required' => 'Password Confirmation is required!',
            'password_confirmation.same' => 'The password Confirmation and password must match!',
        ];
    }
}
