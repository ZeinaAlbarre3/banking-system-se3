<?php

namespace App\Domains\Transaction\Chains;

class TransactionValidationChainFactory
{
    public function __construct(
        private readonly EnsureRelatedAccountProvidedForTransferChain $forTransfer,
        private readonly EnsureSameOwnerForTransferChain $sameOwner,
        private readonly EnsureSufficientBalanceChain $balance,
    ) {}

    public function make(): TransactionChain
    {
        $this->forTransfer
            ->setNext($this->sameOwner)
            ->setNext($this->balance);

        return $this->forTransfer;
    }
}
