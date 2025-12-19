<?php

namespace App\Domains\Notification\Repositories;

use App\Domains\Notification\Models\Notification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface NotificationRepositoryInterface
{
    public function create(array $data): Notification;
    public function forUser(int $userId, int $perPage = 15): LengthAwarePaginator;
    public function markAsRead(Notification $notification): Notification;
}
