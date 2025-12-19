<?php

namespace App\Domains\Notification\Observers;

use App\Domains\Notification\Models\AccountActivity;
use App\Domains\Notification\Repositories\NotificationRepositoryInterface;
use App\Domains\Notification\Enums\AccountActivityTypeEnum;

class AccountActivityObserver
{
    protected static ?NotificationRepositoryInterface $notificationRepo = null;

    public function __construct(NotificationRepositoryInterface $notificationRepo)
    {
        self::$notificationRepo = $notificationRepo;
    }

    public function created(AccountActivity $activity)
    {
        if (!self::$notificationRepo) return;

        $title = $activity->type === AccountActivityTypeEnum::BALANCE_CHANGE->value
            ? 'Balance Updated'
            : 'Large Transaction Alert';

        $body = $activity->type === AccountActivityTypeEnum::LARGE_TRANSACTION->value
            ? 'Your balance was updated.'
            : 'A large transaction occurred.';
        self::$notificationRepo->create([
            'user_id' => $activity->user_id,
            'type'    => $activity->type,
            'title'   => $title,
            'body'    => $body,
            'data'    => [
                'account_id'        => $activity->account_id,
                'activity_reference'=> $activity->reference_number,
                'amount'            => $activity->amount,
                'balance_before'    => $activity->balance_before,
                'balance_after'     => $activity->balance_after,
            ],
        ]);
    }
}

