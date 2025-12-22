<?php

namespace App\Domains\Account\Policies;

use App\Domains\Account\Models\Account;
use App\Domains\Auth\Models\User;

class AccountPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Account $account): bool
    {
        if ($user->hasRole(['admin', 'staff'])) {
            return true;
        }

        if ($user->hasRole('customer')) {
            return $account->user_id === $user->id;
        }

        return false;
    }

    public function update(User $user, Account $account): bool
    {
        return (int) $account->user_id === (int) $user->id;
    }
}
