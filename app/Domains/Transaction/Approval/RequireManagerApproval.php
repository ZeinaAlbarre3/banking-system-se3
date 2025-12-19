<?php

namespace App\Domains\Transaction\Approval;

use App\Domains\Transaction\Data\TransactionApprovalData;
use App\Domains\Transaction\Enums\TransactionStatusEnum;

class RequireManagerApproval extends AbstractApprovalHandler
{
    public function __construct(
        private readonly float $threshold = 1000.00
    ) {}

    protected function process(TransactionApprovalData $data): void
    {
        if ($data->data->amount > $this->threshold) {
            $data->transaction->status = TransactionStatusEnum::PENDING->value;
            $data->transaction->save();
        }
    }
}
