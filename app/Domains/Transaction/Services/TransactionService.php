<?php

namespace App\Domains\Transaction\Services;

use App\Domains\Account\Repositories\AccountRepositoryInterface;
use App\Domains\Transaction\Data\TransactionCreateData;
use App\Domains\Transaction\Models\Transaction;
use App\Domains\Transaction\Chains\EnsureRelatedAccountProvidedForTransfer;
use App\Domains\Transaction\Chains\EnsureSameOwnerForTransfer;
use App\Domains\Transaction\Chains\EnsureSufficientBalance;
use App\Domains\Transaction\Strategies\TransactionStrategyFactory;

class TransactionService
{
    public function __construct(
        private readonly AccountRepositoryInterface              $accounts,
        private readonly TransactionStrategyFactory              $strategyFactory,
        private readonly EnsureRelatedAccountProvidedForTransfer $forTransferRule,
        private readonly EnsureSameOwnerForTransfer              $sameOwnerForTransferRule,
        private readonly EnsureSufficientBalance                 $sufficientBalanceRule,
    ) {}

    public function create(TransactionCreateData $data): Transaction
    {
        $account = $this->accounts->findByReferenceOrFail($data->account_reference);

        $relatedAccount = null;

        if ($data->related_account_reference !== null) {
            $relatedAccount = $this->accounts->findByReferenceOrFail($data->related_account_reference);
        }

        $this->forTransferRule
            ->setNext($this->sameOwnerForTransferRule)
            ->setNext($this->sufficientBalanceRule);

        $this->forTransferRule->validate($account, $data, $relatedAccount);


        $strategy = $this->strategyFactory->forType($data->type);

        return $strategy->execute($account, $data, $relatedAccount);
    }
}
