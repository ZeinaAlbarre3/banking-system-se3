<?php

use App\Domains\Account\Composites\AccountTreeBuilder;
use App\Domains\Account\Models\Account;
use Illuminate\Support\Collection;

it('buildForUser builds a tree and calculates total balance', function () {
    $builder = new AccountTreeBuilder();

    $root1 = new Account(['parent_id' => null, 'balance' => 100]);
    $root1->id = 1;

    $child1 = new Account(['parent_id' => 1, 'balance' => 50]);
    $child1->id = 2;

    $child2 = new Account(['parent_id' => 1, 'balance' => 25]);
    $child2->id = 3;

    $root2 = new Account(['parent_id' => null, 'balance' => 10]);
    $root2->id = 4;

    $accounts = new Collection([$root1, $child1, $child2, $root2]);

    $tree = $builder->buildForUser($accounts);

    expect($tree->getBalance())->toBe(185.0);
});

it('buildForUser nests children under their parent', function () {
    $builder = new AccountTreeBuilder();

    $root = new Account(['parent_id' => null, 'balance' => 10]);
    $root->id = 1;

    $child = new Account(['parent_id' => 1, 'balance' => 5]);
    $child->id=2;

    $accounts = collect([$root, $child]);

    $tree = $builder->buildForUser($accounts);

    $firstRootComponent = $tree->getChildren()[0];

    expect(method_exists($firstRootComponent, 'getChildren'))->toBeTrue()
        ->and(count($firstRootComponent->getChildren()))->toBeGreaterThan(0)
        ->and($tree->getBalance())->toBe(15.0);

});
