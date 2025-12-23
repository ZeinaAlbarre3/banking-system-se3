<?php

namespace App\Domains\Transaction\Approval;

class TransactionApprovalChainFactory
{
    public function make(): ApprovalHandler
    {
        $auto = new AutoApproveSmallTransaction(threshold: 1000);
        $manager = new RequireManagerApproval(threshold: 1000);
        $admin = new RequireAdminApproval(transferThreshold: 2000, anyThreshold: 5000);

        $auto->setNext($manager)->setNext($admin);

        return $auto;
    }
}
