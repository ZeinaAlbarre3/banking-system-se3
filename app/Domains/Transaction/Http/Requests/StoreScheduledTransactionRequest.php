<?php

namespace App\Domains\Transaction\Http\Requests;

use App\Domains\Auth\Enums\OtpActionTypeEnum;
use App\Domains\Transaction\Enums\TransactionTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreScheduledTransactionRequest extends FormRequest
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
            'account_reference' => ['required', 'string', 'exists:accounts,reference_number'],
            'related_account_reference' => ['nullable', 'string', 'exists:accounts,reference_number', 'different:account_reference'],
            'type' => ['required', Rule::enum(TransactionTypeEnum::class)],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['nullable', 'string', 'size:3'],
            'metadata' => ['nullable', 'array'],
            'frequency' => ['required', Rule::in(['daily', 'weekly', 'monthly'])],
            'day_of_week' => ['nullable', 'integer', 'min:1', 'max:7'],
            'day_of_month' => ['nullable', 'integer', 'min:1', 'max:31'],
            'time_of_day' => ['required', 'date_format:H:i'],
            'timezone' => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $frequency = $this->input('frequency');

            if ($frequency === 'weekly' && $this->input('day_of_week') === null) {
                $v->errors()->add('day_of_week', 'day_of_week is required when frequency is weekly.');
            }

            if ($frequency === 'monthly' && $this->input('day_of_month') === null) {
                $v->errors()->add('day_of_month', 'day_of_month is required when frequency is monthly.');
            }
        });
    }
}
