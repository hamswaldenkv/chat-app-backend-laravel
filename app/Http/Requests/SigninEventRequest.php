<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SigninEventRequest extends FormRequest
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
            'event_id'          => 'required',
            'email_address'     => 'required|email',
            'password'          => 'required|min:6',
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
            'event_id.required'         => 'A event_id is required',
            'email_address.required'    => 'A email_address is required',
            'password.required'         => 'A password is required',
        ];
    }
}
