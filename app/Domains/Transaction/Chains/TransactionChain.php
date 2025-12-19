<?php

namespace App\Domains\Transaction\Chains;

use App\Domains\Account\Models\Account;
use App\Domains\Transaction\Data\TransactionCreateData;

interface TransactionChain
{
    public function setNext(?TransactionChain $next): TransactionChain;

    public function handle(Account $account, TransactionCreateData $data, ?Account $relatedAccount = null): void;
}
