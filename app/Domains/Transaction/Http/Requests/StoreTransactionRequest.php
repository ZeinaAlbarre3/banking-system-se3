<?php

namespace App\Domains\Transaction\Http\Requests;

use App\Domains\Auth\Enums\OtpActionTypeEnum;
use App\Domains\Transaction\Enums\TransactionTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransactionRequest extends FormRequest
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
            'type' => ['required', Rule::enum(TransactionTypeEnum::class)],
            'account_reference' => ['required', 'string', 'exists:accounts,reference_number',],
            'amount' => ['required', 'numeric', 'min:0.01',],
            'related_account_reference' => ['nullable', 'string','required_if:type,' . TransactionTypeEnum::TRANSFER->value,'exists:accounts,reference_number', 'different:account_reference',],
            'currency' => ['nullable', 'string', 'size:3',],
            'metadata' => ['nullable', 'array',],
        ];
    }
}
