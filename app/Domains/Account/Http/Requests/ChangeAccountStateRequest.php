<?php

namespace App\Domains\Account\Http\Requests;

use App\Domains\Account\Enums\AccountStateEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeAccountStateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'state' => ['required', Rule::enum(AccountStateEnum::class)],
        ];
    }
}
