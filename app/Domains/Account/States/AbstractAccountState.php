<?php

namespace App\Domains\Account\States;

use App\Domains\Account\Models\Account;

abstract class AbstractAccountState implements AccountState
{
    public function canDeposit(Account $account): bool
    {
        return true;
    }

    public function canWithdraw(Account $account): bool
    {
        return true;
    }

    public function canTransfer(Account $account): bool
    {
        return true;
    }

    public function canClose(Account $account): bool
    {
        return $account->canBeClosed();
    }
}
