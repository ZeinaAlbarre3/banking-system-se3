<?php

namespace App\Domains\Auth\Http\Requests;

use App\Domains\Auth\Enums\OtpActionTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RefreshCodeRequest extends FormRequest
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
            'email' => ['required', 'email', 'exists:users,email'],
            'action' => ['required', 'string',Rule::enum(OtpActionTypeEnum::class)],
        ];
    }
}
