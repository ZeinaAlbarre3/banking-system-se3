<?php

namespace App\Domains\Transaction\Strategies;


use App\Domains\Account\Models\Account;
use App\Domains\Account\Services\AccountService;
use App\Domains\Transaction\Data\TransactionCreateData;
use App\Domains\Transaction\Enums\TransactionStatusEnum;
use App\Domains\Transaction\Exceptions\TransactionRuleException;
use App\Domains\Transaction\Repositories\TransactionRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TransferStrategy implements TransactionStrategy
{
    public function __construct(
        private readonly AccountService                 $accountService,
        private readonly TransactionRepositoryInterface $transactions
    ) {}

    public function execute(Account $account, TransactionCreateData $data, ?Account $relatedAccount = null): \App\Domains\Transaction\Models\Transaction
    {
        $this->accountService->ensureCanTransfer($account);

        if (! $relatedAccount) {
            throw new TransactionRuleException('Related account is required for transfer.');
        }

        return DB::transaction(function () use ($account, $relatedAccount, $data) {

            $account->balance -= $data->amount;
            $account->save();

            $relatedAccount->balance += $data->amount;
            $relatedAccount->save();

            return $this->transactions->create([
                'account_id'        => $account->id,
                'related_account_id'=> $relatedAccount->id,
                'type'              => $data->type->value,
                'status'            => TransactionStatusEnum::COMPLETED->value,
                'amount'            => $data->amount,
                'currency'          => $data->currency ?? 'USD',
                'metadata'          => $data->metadata ?? [],
                'processed_at'      => now(),
            ]);
        });
    }
}
