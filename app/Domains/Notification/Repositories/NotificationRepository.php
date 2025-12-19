<?php

namespace App\Domains\Notification\Repositories;

use App\Domains\Notification\Models\Notification;
use App\Domains\Notification\Repositories\NotificationRepositoryInterface;
use Illuminate\Support\Collection;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function create(array $data): Notification
    {
        return Notification::create($data);
    }

    public function forUser(int $userId, int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator
    {
        return Notification::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function markAsRead(Notification $notification): Notification
    {
        $notification->update(['read' => true]);
        return $notification;
    }
}
