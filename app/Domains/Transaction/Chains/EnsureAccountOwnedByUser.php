<?php

namespace App\Domains\Transaction\Chains;

use App\Domains\Account\Models\Account;
use App\Domains\Transaction\Data\TransactionCreateData;
use App\Domains\Transaction\Enums\TransactionTypeEnum;
use App\Domains\Transaction\Exceptions\TransactionRuleException;
use Illuminate\Support\Facades\Auth;

class EnsureAccountOwnedByUser extends AbstractTransactionChain
{
    public function process(Account $account, $data, ?Account $relatedAccount = null): void
    {
        $user = Auth::user();

        if ($user && $user->hasRole(['admin', 'manager' , 'staff'])) {
            return;
        }

        if (! $user || (int)$account->user_id !== (int)$user->id) {
            throw new TransactionRuleException('You can only perform transactions on your own accounts.');
        }
    }
}
