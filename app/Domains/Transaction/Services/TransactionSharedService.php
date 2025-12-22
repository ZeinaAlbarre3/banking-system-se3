<?php

namespace App\Domains\Transaction\Services;

use App\Domains\Account\Models\Account;
use App\Domains\Account\Repositories\AccountRepositoryInterface;
use App\Domains\Transaction\Chains\TransactionValidationChainFactory;
use App\Domains\Transaction\Data\TransactionCreateData;

class TransactionSharedService
{
    public function __construct(
        private readonly AccountRepositoryInterface        $accounts,
        private readonly TransactionValidationChainFactory $validationChainFactory,
    ) {}
    public function resolveAccounts($data): array
    {
        $account = $this->accounts->findByReferenceOrFail($data->account_reference);

        $related = null;
        if ($data->related_account_reference !== null) {
            $related = $this->accounts->findByReferenceOrFail($data->related_account_reference);
        }

        return [$account, $related];
    }

    public function validate(Account $account, $data, ?Account $related): void
    {
        $this->validationChainFactory->make()->handle($account, $data, $related);
    }

}
