<?php

use App\Domains\Auth\Models\User;
use App\Domains\Account\Models\Account;
use App\Domains\Account\Enums\AccountStateEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate('customer');
    Role::findOrCreate('admin');
    Role::findOrCreate('staff');
});


it('customer can deposit to own account', function () {
    $user = loginWithPerm(User::factory()->create(), ['create-transaction']);

    $account = Account::factory()->for($user)->create([
        'state' => AccountStateEnum::ACTIVE->value,
        'balance' => 0,
    ]);

    $payload = [
        'type' => 'deposit',
        'account_reference' => $account->reference_number,
        'amount' => 20,
        'currency' => 'USD',
        'metadata' => [],
    ];

    $res = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/transactions', $payload);

    $res->assertOk()
        ->assertJsonPath('status', 1);

    $account->refresh();
    expect((float) $account->balance)->toBe(20.0);
});


it('customer can withdraw from own account when balance is sufficient', function () {
    $user = loginWithPerm(User::factory()->create(), ['create-transaction']);

    $account = Account::factory()->for($user)->create([
        'state' => AccountStateEnum::ACTIVE->value,
        'balance' => 100,
    ]);

    $payload = [
        'type' => 'withdraw',
        'account_reference' => $account->reference_number,
        'amount' => 40,
        'currency' => 'USD',
        'metadata' => [],
    ];

    $res = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/transactions', $payload);

    $res->assertOk()
        ->assertJsonPath('status', 1);

    $account->refresh();
    expect((float) $account->balance)->toBe(60.0);
});


it('customer cannot withdraw when balance is insufficient', function () {
    $user = loginWithPerm(User::factory()->create(), ['create-transaction']);

    $account = Account::factory()->for($user)->create([
        'state' => AccountStateEnum::ACTIVE->value,
        'balance' => 10,
    ]);

    $payload = [
        'type' => 'withdraw',
        'account_reference' => $account->reference_number,
        'amount' => 50,
        'currency' => 'USD',
    ];

    $res = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/transactions', $payload);

    $res->assertStatus(422)
        ->assertJsonPath('status', 0);

    $account->refresh();
    expect((float) $account->balance)->toBe(10.0);
});


it('customer can transfer between own accounts', function () {
    $user = loginWithPerm(User::factory()->create(), ['create-transaction']);

    $from = Account::factory()->for($user)->create([
        'state' => AccountStateEnum::ACTIVE->value,
        'balance' => 100,
    ]);

    $to = Account::factory()->for($user)->create([
        'state' => AccountStateEnum::ACTIVE->value,
        'balance' => 10,
    ]);

    $payload = [
        'type' => 'transfer',
        'account_reference' => $from->reference_number,
        'related_account_reference' => $to->reference_number,
        'amount' => 30,
        'currency' => 'USD',
    ];

    $res = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/transactions', $payload);

    $res->assertOk()
        ->assertJsonPath('status', 1);

    $from->refresh();
    $to->refresh();

    expect((float) $from->balance)->toBe(70.0)
        ->and((float) $to->balance)->toBe(40.0);
});


it('customer cannot transfer without related account reference', function () {
    $user = loginWithPerm(User::factory()->create(), ['create-transaction']);

    $from = Account::factory()->for($user)->create([
        'state' => AccountStateEnum::ACTIVE->value,
        'balance' => 100,
    ]);

    $payload = [
        'type' => 'transfer',
        'account_reference' => $from->reference_number,
        'amount' => 10,
        'currency' => 'USD',
    ];

    $res = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/transactions', $payload);

    $res->assertStatus(422)
        ->assertJsonPath('status', 0);
});


it('customer cannot transfer to another users account', function () {
    $user = loginWithPerm(User::factory()->create(), ['create-transaction']);
    $other = User::factory()->create();

    $from = Account::factory()->for($user)->create([
        'state' => AccountStateEnum::ACTIVE->value,
        'balance' => 100,
    ]);

    $to = Account::factory()->for($other)->create([
        'state' => AccountStateEnum::ACTIVE->value,
        'balance' => 0,
    ]);

    $payload = [
        'type' => 'transfer',
        'account_reference' => $from->reference_number,
        'related_account_reference' => $to->reference_number,
        'amount' => 10,
        'currency' => 'USD',
    ];

    $res = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/transactions', $payload);

    $res->assertStatus(422)
        ->assertJsonPath('status', 0);

    $from->refresh();
    $to->refresh();

    expect((float) $from->balance)->toBe(100.0)
        ->and((float) $to->balance)->toBe(0.0);
});


it('customer cannot deposit into closed account', function () {
    $user = loginWithPerm(User::factory()->create(), ['create-transaction']);

    $account = Account::factory()->for($user)->create([
        'state' => AccountStateEnum::CLOSED->value,
        'balance' => 0,
    ]);

    $payload = [
        'type' => 'deposit',
        'account_reference' => $account->reference_number,
        'amount' => 10,
        'currency' => 'USD',
    ];

    $res = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/transactions', $payload);

    $res->assertStatus(422)
        ->assertJsonPath('status', 0);

    $account->refresh();
    expect((float) $account->balance)->toBe(0.0);
});
