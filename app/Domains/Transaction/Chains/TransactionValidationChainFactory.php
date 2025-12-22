<?php

namespace App\Domains\Transaction\Chains;

class TransactionValidationChainFactory
{
    public function __construct(
        private readonly EnsureRelatedAccountProvidedForTransferChain $forTransfer,
        private readonly EnsureSameOwnerForTransferChain $sameOwner,
        private readonly EnsureSufficientBalanceChain $balance,
        private readonly EnsureAccountOwnedByUser $accountOwnedByUser,
    ) {}

    public function make(): TransactionChain
    {
        $this->accountOwnedByUser
            ->setNext($this->forTransfer)
            ->setNext($this->sameOwner)
            ->setNext($this->balance);

        return $this->accountOwnedByUser;
    }
}
