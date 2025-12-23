<?php

namespace App\Domains\Report\Data;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Domains\Transaction\Enums\TransactionTypeEnum;
use App\Domains\Transaction\Enums\TransactionStatusEnum;

class TransactionReportFilterData
{
    public function __construct(
        public ?string $date_from,
        public ?string $date_to,
        public ?string $type,
        public ?string $status,
        public ?int $account_id,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $validator = Validator::make($request->all(), self::rules());

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return new self(
            $request->date_from,
            $request->date_to,
            $request->type,
            $request->status,
            $request->account_id,
        );
    }

    public static function rules(): array
    {
        return [
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],

            'type' => [
                'nullable',
                'in:' . implode(',', array_column(TransactionTypeEnum::cases(), 'value')),
            ],

            'status' => [
                'nullable',
                'in:' . implode(',', array_column(TransactionStatusEnum::cases(), 'value')),
            ],

            'account_id' => ['nullable', 'integer', 'exists:accounts,id'],
        ];
    }
}
