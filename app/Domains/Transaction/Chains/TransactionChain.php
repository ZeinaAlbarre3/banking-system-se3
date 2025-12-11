<?php

namespace App\Domains\Transaction\Chains;

use App\Domains\Account\Models\Account;
use App\Domains\Transaction\Data\TransactionCreateData;
use App\Domains\Transaction\Models\Transaction;

interface TransactionChain
{
    public function setNext(?TransactionChain $next): TransactionChain;

    public function validate(Account $account, TransactionCreateData $data, ?Account $relatedAccount = null): void;
}
