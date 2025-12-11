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
            'name'      => ['sometimes', 'string','max:255'],
            'type'      => ['sometimes', Rule::enum(AccountTypeEnum::class)],
            'parent_reference' => ['sometimes', 'nullable', 'string', 'exists:accounts,reference_number'],
            'metadata'  => ['sometimes', 'nullable', 'array'],
        ];
    }
}
