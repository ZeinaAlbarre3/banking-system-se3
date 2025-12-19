<?php

namespace App\Domains\Transaction\Strategies;


use App\Domains\Account\Models\Account;
use App\Domains\Account\Services\AccountService;
use App\Domains\Transaction\Data\TransactionCreateData;
use App\Domains\Transaction\Exceptions\TransactionRuleException;

class TransferStrategy implements TransactionStrategy
{
    public function __construct(
        private readonly AccountService                 $accountService,
    )
    {
    }

    public function apply(Account $account, TransactionCreateData $data, ?Account $relatedAccount = null): void
    {
        $this->accountService->ensureCanTransfer($account);

        if (!$relatedAccount) {
            throw new TransactionRuleException('Related account is required for transfer.');
        }

        $account->balance -= $data->amount;
        $account->save();

        $relatedAccount->balance += $data->amount;
        $relatedAccount->save();
    }
}
