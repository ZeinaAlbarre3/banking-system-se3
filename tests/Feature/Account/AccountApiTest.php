<?php

use App\Domains\Account\Models\Account;
use App\Domains\Auth\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate('customer');
    Role::findOrCreate('admin');
    Role::findOrCreate('staff');
});


it('creates account with balance 0 by default', function () {
    $user = loginWithPerm(User::factory()->create(), ['create-account']);

    $payload = [
        'user_id' => $user->id,
        'name' => 'Main Account',
        'type' => 'current',
        'parent_reference' => null,
    ];

    $res = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/accounts', $payload);

    $res->assertOk()
        ->assertJsonPath('status', 1);

    $account = Account::query()->first();

    expect($account)->not->toBeNull()
        ->and($account->user_id)->toBe($user->id)
        ->and((float)$account->balance)->toBe(0.0);
});


it('customer cannot view account of another user', function () {
    $customer = loginWithPerm(User::factory()->create(), ['view-account']);

    $other = User::factory()->create();
    $other->assignRole('customer');

    $otherAccount = Account::factory()->for($other)->create();

    $res = $this->actingAs($customer, 'sanctum')
        ->getJson("/api/v1/accounts/{$otherAccount->reference_number}");

    $res->assertServerError(); // 500
});

it('customer can view own account', function () {
    $customer = loginWithPerm(User::factory()->create(), ['view-account']);

    $myAccount = Account::factory()->for($customer)->create();

    $res = $this->actingAs($customer, 'sanctum')
        ->getJson("/api/v1/accounts/{$myAccount->reference_number}");

    $res->assertOk()->assertJsonPath('status', 1);
});

it('returns only authenticated user accounts in index', function () {
    $user = loginWithPerm(User::factory()->create(), ['view-my-accounts']);

    $other = User::factory()->create();

    Account::factory()->count(2)->for($user)->create();
    Account::factory()->count(3)->for($other)->create();

    $res = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/accounts/my');

    $res->assertOk()
        ->assertJsonPath('status', 1);

    $data = $res->json('data');
    expect(count($data))->toBe(2);
});

it('cannot create child account with parent of different owner', function () {
    $user = loginWithPerm(User::factory()->create(), ['create-account']);

    $other = User::factory()->create();
    $parent = Account::factory()->for($other)->create();

    $payload = [
        'name' => 'Child',
        'type' => 'saving',
        'parent_reference' => $parent->reference_number,
    ];

    $res = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/accounts', $payload);

    $res->assertStatus(422);
});
