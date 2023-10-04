<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
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
            'email_address'     => 'required|email|unique:users,email',
            'password'          => 'required|min:8',
            'first_name'        => 'required|min:2',
            'last_name'         => 'required|min:2',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email_address.required'    => 'A email_address is required',
            'profile_name.required'     => 'A profile_name is required',
        ];
    }
}
