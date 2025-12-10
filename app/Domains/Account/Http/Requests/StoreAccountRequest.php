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
            'type'      => ['required',Rule::enum(AccountTypeEnum::class)],
            'parent_id' => ['nullable', 'integer', 'exists:accounts,id'],
            'metadata'  => ['nullable', 'array'],
        ];
    }
}
