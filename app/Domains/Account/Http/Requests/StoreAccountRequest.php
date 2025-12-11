<?php

namespace App\Domains\Account\Http\Requests;

use App\Domains\Account\Enums\AccountTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type'      => ['required',Rule::enum(AccountTypeEnum::class)],
            'parent_reference' => ['nullable', 'string', 'exists:accounts,reference_number'],
            'metadata'  => ['nullable', 'array'],
        ];
    }
}
