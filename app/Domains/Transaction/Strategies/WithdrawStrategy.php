<?php

namespace App\Domains\Transaction\Strategies;


use App\Domains\Account\Models\Account;
use App\Domains\Account\Services\AccountService;
use App\Domains\Transaction\Data\TransactionCreateData;

class WithdrawStrategy implements TransactionStrategy
{
    public function __construct(
        private readonly AccountService                 $accountService,
    ) {}

    public function apply(Account $account, TransactionCreateData $data, ?Account $relatedAccount = null): void
    {
        $this->accountService->ensureCanWithdraw($account);

        $account->balance -= $data->amount;
        $account->save();
    }
}
