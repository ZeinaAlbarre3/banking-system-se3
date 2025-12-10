<?php

namespace App\Domains\Account\States;

use App\Domains\Account\Models\Account;
class SuspendedState extends AbstractAccountState
{
    public function name(): string
    {
        return 'suspended';
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
}
