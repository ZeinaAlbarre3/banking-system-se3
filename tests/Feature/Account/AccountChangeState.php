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


it('customer can close own account when balance is zero', function () {
    $customer = loginWithPerm(User::factory()->create(), ['update-account']);

    $account = Account::factory()
        ->for($customer)
        ->create([
            'balance' => 0,
            'state' => AccountStateEnum::ACTIVE->value,
        ]);

    $payload = [
        'state' => AccountStateEnum::CLOSED->value,
    ];

    $res = $this->actingAs($customer, 'sanctum')
        ->patchJson("/api/v1/accounts/{$account->reference_number}/state", $payload);

    $res->assertOk()
        ->assertJsonPath('status', 1);

    $account->refresh();
    expect($account->state)->toBe(AccountStateEnum::CLOSED->value);
});

it('customer cannot close own account when balance is not zero (should return 422)', function () {
    $customer = loginWithPerm(User::factory()->create(), ['update-account']);

    $account = Account::factory()
        ->for($customer)
        ->create([
            'balance' => 50,
            'state' => AccountStateEnum::ACTIVE->value,
        ]);

    $payload = [
        'state' => AccountStateEnum::CLOSED->value,
    ];

    $res = $this->actingAs($customer, 'sanctum')
        ->patchJson("/api/v1/accounts/{$account->reference_number}/state", $payload);

    $res->assertStatus(422)
        ->assertJsonPath('status', 0);

    $account->refresh();
    expect($account->state)->toBe(AccountStateEnum::ACTIVE->value);
});

/*it('customer cannot change state of another users account (should be forbidden)', function () {
    $customer = User::factory()->create();
    $customer->assignRole('customer');
    givePermissions($customer, ['update-account']);

    $other = User::factory()->create();
    $otherAccount = Account::factory()->for($other)->create([
        'balance' => 0,
        'state' => AccountStateEnum::ACTIVE->value,
    ]);

    $payload = [
        'state' => AccountStateEnum::CLOSED->value,
    ];

    $res = $this->actingAs($customer, 'sanctum')
        ->patchJson("/api/v1/accounts/{$otherAccount->reference_number}/state", $payload);

    $res->assertForbidden();
});*/

it('returns 422 when state value is invalid', function () {
    $customer = loginWithPerm(User::factory()->create(), ['update-account']);

    $account = Account::factory()->for($customer)->create();

    $payload = [
        'state' => 'wrong_state',
    ];

    $res = $this->actingAs($customer, 'sanctum')
        ->patchJson("/api/v1/accounts/{$account->reference_number}/state", $payload);

    $res->assertStatus(422)
        ->assertJsonPath('status', 0);
});
