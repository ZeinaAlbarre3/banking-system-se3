<?php

use App\Domains\Account\Models\Account;
use App\Domains\Account\States\AccountStateFactory;

it('active state allows deposit/withdraw/transfer', function () {
    $acc = new Account(['state' => 'active', 'balance' => 0]);

    $state = AccountStateFactory::fromAccount($acc);

    expect($state->canDeposit($acc))->toBeTrue()
        ->and($state->canWithdraw($acc))->toBeTrue()
        ->and($state->canTransfer($acc))->toBeTrue();
});

it('closed state blocks deposit/withdraw/transfer/close', function () {
    $acc = new Account(['state' => 'closed', 'balance' => 0]);

    $state = AccountStateFactory::fromAccount($acc);

    expect($state->canDeposit($acc))->toBeFalse()
        ->and($state->canWithdraw($acc))->toBeFalse()
        ->and($state->canTransfer($acc))->toBeFalse()
        ->and($state->canClose($acc))->toBeFalse();
});
