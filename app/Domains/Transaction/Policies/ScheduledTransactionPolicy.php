<?php

namespace App\Domains\Transaction\Policies;

use App\Domains\Auth\Models\User;
use App\Domains\Transaction\Models\ScheduledTransaction;

class ScheduledTransactionPolicy
{
    public function view(User $user, ScheduledTransaction $schedule): bool
    {
        return (int) $schedule->user_id === (int) $user->id;
    }

    public function update(User $user, ScheduledTransaction $schedule): bool
    {
        return (int) $schedule->user_id === (int) $user->id;
    }

    public function delete(User $user, ScheduledTransaction $schedule): bool
    {
        return (int) $schedule->user_id === (int) $user->id;
    }
}
