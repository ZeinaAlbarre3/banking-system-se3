<?php

namespace App\Domains\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['bail', 'required', 'string', 'max:255'],

            'email' => ['bail', 'required', 'email', 'max:255', Rule::unique('users', 'email')->whereNotNull('email_verified_at')],

            'password' => [
                'bail',
                'required',
                'confirmed',
                Password::min(8)
            ],

        ];
    }


}
