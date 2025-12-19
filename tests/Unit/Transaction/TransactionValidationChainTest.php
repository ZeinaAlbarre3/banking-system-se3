<?php

use App\Domains\Account\Models\Account;
use App\Domains\Transaction\Chains\EnsureRelatedAccountProvidedForTransferChain;
use App\Domains\Transaction\Chains\EnsureSameOwnerForTransferChain;
use App\Domains\Transaction\Chains\EnsureSufficientBalanceChain;
use App\Domains\Transaction\Data\TransactionCreateData;
use App\Domains\Transaction\Enums\TransactionTypeEnum;
use App\Domains\Transaction\Exceptions\TransactionRuleException;

it('transfer requires related account (chain)', function () {
    $account = new Account(['user_id' => 1, 'balance' => 100]); $account->id = 1;

    $data = new TransactionCreateData(
        type: TransactionTypeEnum::TRANSFER,
        account_reference: 'ACC-1',
        amount: 10,
        related_account_reference: null,
        currency: 'USD',
        metadata: null
    );

    $chain = new EnsureRelatedAccountProvidedForTransferChain();
    $chain->setNext(new EnsureSameOwnerForTransferChain())
        ->setNext(new EnsureSufficientBalanceChain());

    expect(fn () => $chain->process($account, $data, null))
        ->toThrow(TransactionRuleException::class);
});
