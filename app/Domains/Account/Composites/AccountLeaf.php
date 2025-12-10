<?php

namespace App\Domains\Account\Composites;

use App\Domains\Account\Models\Account;

class AccountLeaf implements AccountComponent
{
    public function __construct(
        protected Account $account
    ) {}

    public function getBalance(): float
    {
        return (float) $this->account->balance;
    }

    public function getModel(): Account
    {
        return $this->account;
    }
}
