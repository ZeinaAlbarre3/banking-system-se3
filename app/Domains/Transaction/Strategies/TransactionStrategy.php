<?php

namespace App\Domains\Transaction\Strategies;

use App\Domains\Account\Models\Account;
use App\Domains\Transaction\Data\TransactionCreateData;
use App\Domains\Transaction\Models\Transaction;

interface TransactionStrategy
{
    public function execute(Account $account, TransactionCreateData $data, ?Account $relatedAccount = null): Transaction;
}
