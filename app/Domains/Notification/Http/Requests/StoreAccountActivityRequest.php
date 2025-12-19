<?php
namespace App\Domains\Notification\Http\Requests;
use App\Domains\Notification\Enums\AccountActivityTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAccountActivityRequest extends FormRequest
{
    public function authorize(): bool
    {

        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(AccountActivityTypeEnum::class)],
            'account_reference' => ['required', 'string', 'exists:accounts,reference_number',],
            'amount' => ['nullable','numeric'],
            'meta' => ['nullable','array'],
        ];
    }
}
