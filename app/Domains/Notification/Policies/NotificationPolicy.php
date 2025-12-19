<?php


namespace App\Domains\Notification\Policies;

use App\Domains\Auth\Models\User;
use App\Domains\Notification\Models\Notification;

class NotificationPolicy
{
    public function view(User $user, Notification $notification): bool
    {
        if ($user->hasRole(['admin', 'employee'])) {
            return true;
        }

        if ($user->hasRole('customer')) {
            return $notification->user_id === $user->id;
        }

        return false;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'employee', 'customer']);
    }
}
