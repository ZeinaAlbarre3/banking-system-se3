<?php

namespace App\Domains\Transaction\Approval;

use App\Domains\Transaction\Data\TransactionApprovalData;

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
        $this->process($data);

        if ($this->next) {
            $this->next->handle($data);
        }
    }

    abstract protected function process(TransactionApprovalData $data): void;
}
