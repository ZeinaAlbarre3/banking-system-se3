<?php

use App\Domains\Notification\Observers\AccountActivityObserver;
use App\Domains\Notification\Models\AccountActivity;
use App\Domains\Notification\Enums\AccountActivityTypeEnum;
use App\Domains\Notification\Repositories\NotificationRepositoryInterface;

it('creates notification when account activity is created', function () {


    $notificationRepo = Mockery::mock(NotificationRepositoryInterface::class);



    $notificationRepo->shouldReceive('create')
        ->once()
        ->with(Mockery::on(function ($data) {
            return
                $data['user_id'] === 1 &&
                $data['type'] === AccountActivityTypeEnum::BALANCE_CHANGE->value &&
                $data['title'] === 'Balance Updated' &&
                $data['body'] === 'A large transaction occurred.';
        }));


    $observer = new AccountActivityObserver($notificationRepo);


    $activity = new AccountActivity([
        'user_id'         => 1,
        'account_id'      => 10,
        'type'            => AccountActivityTypeEnum::BALANCE_CHANGE->value,
        'reference_number'=> 'REF123',
        'amount'          => 100,
        'balance_before' => 500,
        'balance_after'  => 600,
    ]);


    $observer->created($activity);
});

