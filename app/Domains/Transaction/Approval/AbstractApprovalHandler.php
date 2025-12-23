<?php

namespace App\Domains\Transaction\Approval;

use App\Domains\Transaction\Data\TransactionApprovalData;
use App\Domains\Transaction\Enums\TransactionStatusEnum;

abstract class AbstractApprovalHandler implements ApprovalHandler
{
    protected ?ApprovalHandler $next = null;

    public function setNext(?ApprovalHandler $next): ApprovalHandler
    {
        $this->next = $next;
        return $next ?? $this;
    }

    public function handle(TransactionApprovalData $data): void
    {
        if (in_array($data->transaction->status, [
            TransactionStatusEnum::COMPLETED->value,
        ], true)) {
            return;
        }

        $this->process($data);

        if ($this->next) {
            $this->next->handle($data);
        }
    }

    abstract protected function process(TransactionApprovalData $data): void;
}
