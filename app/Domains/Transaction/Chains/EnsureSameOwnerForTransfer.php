<?php

namespace App\Domains\Transaction\Chains;


use App\Domains\Account\Models\Account;
use App\Domains\Transaction\Data\TransactionCreateData;
use App\Domains\Transaction\Enums\TransactionTypeEnum;
use App\Domains\Transaction\Exceptions\TransactionRuleException;

class EnsureSameOwnerForTransfer extends AbstractTransactionChain
{
    protected function handle(Account $account, TransactionCreateData $data, ?Account $relatedAccount = null): void
    {
        if ($data->type !== TransactionTypeEnum::TRANSFER || ! $relatedAccount) {
            return;
        }

        if ($account->user_id !== $relatedAccount->user_id) {
            throw new TransactionRuleException('Transfer must be between accounts of the same owner.');
        }
    }
}
