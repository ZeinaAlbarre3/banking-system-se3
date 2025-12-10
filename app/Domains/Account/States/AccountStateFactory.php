<?php

namespace App\Domains\Account\States;

use App\Domains\Account\Models\Account;
use InvalidArgumentException;

class AccountStateFactory
{
    public static function fromAccount(Account $account): AccountState
    {
        return self::fromString($account->state);
    }

    public static function fromString(string $state): AccountState
    {
        return match ($state) {
            'active'    => new ActiveState(),
            'frozen'    => new FrozenState(),
            'suspended' => new SuspendedState(),
            'closed'    => new ClosedState(),
            default     => throw new InvalidArgumentException("Unknown account state: {$state}"),
        };
    }
}
