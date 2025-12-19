<?php

use App\Domains\Account\Composites\AccountLeaf;
use App\Domains\Account\Models\Account;
use App\Domains\Account\Composites\AccountGroup;

it('AccountLeaf returns its account balance', function () {
    $account = new Account(['balance' => 25]);

    $leaf = new AccountLeaf($account);

    expect($leaf->getBalance())->toBe(25.0);
});

it('AccountGroup sums balances of its children', function () {
    $a1 = new AccountLeaf(new Account(['balance' => 10]));
    $a2 = new AccountLeaf(new Account(['balance' => 15]));

    $group = new AccountGroup();
    $group->add($a1);
    $group->add($a2);

    expect($group->getBalance())->toBe(25.0);
});

it('AccountGroup sums nested groups recursively', function () {
    $leaf1 = new AccountLeaf(new Account(['balance' => 10]));
    $leaf2 = new AccountLeaf(new Account(['balance' => 20]));

    $inner = new AccountGroup();
    $inner->add($leaf2);

    $outer = new AccountGroup();
    $outer->add($leaf1);
    $outer->add($inner);

    expect($outer->getBalance())->toBe(30.0);
});
