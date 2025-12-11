<?php

namespace App\Domains\Transaction\Chains;

use App\Domains\Account\Models\Account;
use App\Domains\Transaction\Data\TransactionCreateData;
use App\Domains\Transaction\Enums\TransactionTypeEnum;
use App\Domains\Transaction\Exceptions\TransactionRuleException;

class EnsureRelatedAccountProvidedForTransfer extends AbstractTransactionChain
{
    protected function handle(Account $account, TransactionCreateData $data, ?Account $relatedAccount = null): void
    {
        if ($data->type !== TransactionTypeEnum::TRANSFER) {
            return;
        }

        if (! $relatedAccount) {
            throw new TransactionRuleException(
                'Related account is required for transfer.'
            );
        }
    }
}
