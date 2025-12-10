<?php

namespace App\Domains\Account\Composites;

class AccountGroup implements AccountComponent
{
    protected array $children = [];

    public function add(AccountComponent $component): void
    {
        $this->children[] = $component;
    }

    public function getBalance(): float
    {
        return array_reduce(
            $this->children,
            fn (float $carry, AccountComponent $component) => $carry + $component->getBalance(),
            0.0
        );
    }

    public function getChildren(): array
    {
        return $this->children;
    }
}
