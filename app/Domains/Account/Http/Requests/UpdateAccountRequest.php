<?php

namespace App\Domains\Account\Http\Requests;

use App\Domains\Account\Enums\AccountTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'      => ['sometimes', Rule::enum(AccountTypeEnum::class)],
            'parent_id' => ['sometimes', 'nullable', 'integer', 'exists:accounts,id'],
            'metadata'  => ['sometimes', 'nullable', 'array'],
        ];
    }
}
