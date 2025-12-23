<?php

use App\Domains\Account\Models\Account;
use App\Domains\Auth\Models\User;
use App\Domains\Notification\Models\AccountActivity;
use App\Domains\Notification\Enums\AccountActivityTypeEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates notification record when account activity is created', function () {

    $user = User::factory()->create();

    $account = Account::factory()->create([
        'user_id' => $user->id,
    ]);

    AccountActivity::factory()->create([
        'user_id'    => $user->id,
        'account_id' => $account->id,
        'type'       => AccountActivityTypeEnum::LARGE_TRANSACTION->value,
        'amount'     => 100,
        'balance_before' => 1000,
        'balance_after'  => 1100,
        'meta' => [],
    ]);

    $this->assertDatabaseHas('notifications', [
        'user_id' => $user->id,
        'type'    => AccountActivityTypeEnum::LARGE_TRANSACTION->value,
    ]);
});
