<?php

namespace App\Domains\Account\States;

use App\Domains\Account\Models\Account;

interface AccountState
{
    public function name(): string;

    public function canDeposit(Account $account): bool;

    public function canWithdraw(Account $account): bool;

    public function canTransfer(Account $account): bool;

    public function canClose(Account $account): bool;
}
