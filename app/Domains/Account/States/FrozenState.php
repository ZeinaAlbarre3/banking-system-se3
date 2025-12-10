<?php

namespace App\Domains\Account\States;

use App\Domains\Account\Models\Account;

class FrozenState extends AbstractAccountState
{
    public function name(): string
    {
        return 'frozen';
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
