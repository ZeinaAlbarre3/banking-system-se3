<?php

use App\Domains\Notification\Models\AccountActivity;
use App\Domains\Notification\Enums\AccountActivityTypeEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates notification record when account activity is created', function () {


    $activity = AccountActivity::factory()->create([
        'user_id' => 1,
        'type'    => AccountActivityTypeEnum::LARGE_TRANSACTION->value,
    ]);


    $this->assertDatabaseHas('notifications', [
        'user_id' => 1,
        'type'    => AccountActivityTypeEnum::LARGE_TRANSACTION->value,
        'title'   => 'Large Transaction Alert',
        'body'    => 'Your balance was updated.',
    ]);
});
