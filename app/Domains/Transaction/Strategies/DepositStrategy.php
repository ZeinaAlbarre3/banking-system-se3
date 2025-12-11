<?php

namespace App\Domains\Transaction\Strategies;

use App\Domains\Account\Models\Account;
use App\Domains\Account\Services\AccountService;
use App\Domains\Transaction\Data\TransactionCreateData;
use App\Domains\Transaction\Enums\TransactionStatusEnum;
use App\Domains\Transaction\Models\Transaction;
use App\Domains\Transaction\Repositories\TransactionRepositoryInterface;

class DepositStrategy implements TransactionStrategy
{
    public function __construct(
        private readonly AccountService                 $accountService,
        private readonly TransactionRepositoryInterface $transactions
    ) {}

    public function execute(Account $account, TransactionCreateData $data, ?Account $relatedAccount = null): Transaction
    {
        $this->accountService->ensureCanDeposit($account);

        $account->balance += $data->amount;
        $account->save();

        return $this->transactions->create([
            'account_id'        => $account->id,
            'related_account_reference'=> null,
            'type'              => $data->type->value,
            'status'            => TransactionStatusEnum::COMPLETED->value,
            'amount'            => $data->amount,
            'currency'          => $data->currency ?? 'USD',
            'metadata'          => $data->metadata ?? [],
            'processed_at'      => now(),
        ]);
    }
}
