<?php

namespace App\Domains\Transaction\Approval;

use App\Domains\Transaction\Data\TransactionApprovalData;

interface ApprovalHandler
{
    public function setNext(?ApprovalHandler $next): ApprovalHandler;
    public function handle(TransactionApprovalData $data): void;
}
