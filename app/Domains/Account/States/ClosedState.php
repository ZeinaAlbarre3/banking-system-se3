<?php

namespace App\Domains\Account\States;

use App\Domains\Account\Models\Account;
class ClosedState extends AbstractAccountState
{
    public function name(): string
    {
        return 'closed';
    }

    public function canDeposit(Account $account): bool
    {
        return false;
    }

    public function canWithdraw(Account $account): bool
    {
        return false;
    }

    public function canTransfer(Account $account): bool
    {
        return false;
    }
    public function canClose(Account $account): bool
    {
        return false;
    }
}
