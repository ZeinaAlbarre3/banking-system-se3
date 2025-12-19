<?php

namespace App\Domains\Transaction\Approval;

use App\Domains\Transaction\Data\TransactionApprovalData;
use App\Domains\Transaction\Enums\TransactionStatusEnum;
use App\Domains\Transaction\Enums\TransactionTypeEnum;

class RequireAdminApproval extends AbstractApprovalHandler
{
    public function __construct(
        private readonly float $transferThreshold = 2000.00,
        private readonly float $anyThreshold = 5000.00,
    ) {}

    protected function process(TransactionApprovalData $data): void
    {
        $isHuge = $data->data->amount >= $this->anyThreshold;

        $isTransfer =
            $data->data->type === TransactionTypeEnum::TRANSFER
            && $data->data->amount >= $this->transferThreshold;

        if ($isHuge || $isTransfer) {
            $data->transaction->status = TransactionStatusEnum::PENDING->value;
            $data->transaction->save();
        }
    }
}
