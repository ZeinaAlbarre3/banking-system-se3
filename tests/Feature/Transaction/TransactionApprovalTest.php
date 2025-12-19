<?php

use App\Domains\Account\Models\Account;
use App\Domains\Auth\Models\User;
use App\Domains\Transaction\Enums\TransactionStatusEnum;
use App\Domains\Transaction\Enums\TransactionTypeEnum;
use App\Domains\Transaction\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('auto approves small transaction and updates balance', function () {
    $user = User::factory()->create();
    givePermissions($user, ['create-transaction']);

    $account = Account::factory()->create([
        'user_id' => $user->id,
        'balance' => 1000,
    ]);

    $payload = [
        'type' => TransactionTypeEnum::DEPOSIT->value,
        'account_reference' => $account->reference_number,
        'amount' => 100,
        'currency' => 'USD',
    ];

    $res = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/transactions', $payload);

    $res->assertOk()
        ->assertJsonPath('status', 1);

    $transaction = Transaction::first();
    $account->refresh();

    expect($transaction->status)->toBe(TransactionStatusEnum::COMPLETED->value)
        ->and((float)$account->balance)->toBe(1100.0);
});

it('creates pending transaction when amount requires approval', function () {
    $user = User::factory()->create();
    givePermissions($user, ['create-transaction']);

    $account = Account::factory()->create([
        'user_id' => $user->id,
        'balance' => 5000,
    ]);

    $payload = [
        'type' => TransactionTypeEnum::WITHDRAW->value,
        'account_reference' => $account->reference_number,
        'amount' => 2000,
        'currency' => 'USD',
    ];

    $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/transactions', $payload)
        ->assertOk();

    $transaction = Transaction::first();
    $account->refresh();

    expect($transaction->status)->toBe(TransactionStatusEnum::PENDING->value)
        ->and((float)$account->balance)->toBe(5000.0);

});

it('admin can approve pending transaction and apply balance changes', function () {
    $admin = User::factory()->create();
    givePermissions($admin, ['approve-transaction']);

    $account = Account::factory()->create([
        'balance' => 3000,
    ]);

    $transaction = Transaction::factory()->create([
        'account_id' => $account->id,
        'type' => TransactionTypeEnum::WITHDRAW->value,
        'amount' => 1000,
        'status' => TransactionStatusEnum::PENDING->value,
    ]);

    $this->actingAs($admin, 'sanctum')
        ->patchJson("/api/v1/transactions/{$transaction->reference_number}/approve")
        ->assertOk();

    $transaction->refresh();
    $account->refresh();

    expect($transaction->status)->toBe(TransactionStatusEnum::APPROVED->value)
        ->and((float)$account->balance)->toBe(2000.0);
});

it('admin can reject transaction without changing balance', function () {
    $admin = User::factory()->create();
    givePermissions($admin, ['reject-transaction']);

    $account = Account::factory()->create([
        'balance' => 3000,
    ]);

    $transaction = Transaction::factory()->create([
        'account_id' => $account->id,
        'amount' => 1500,
        'status' => TransactionStatusEnum::PENDING->value,
    ]);

    $this->actingAs($admin, 'sanctum')
        ->patchJson("/api/v1/transactions/{$transaction->reference_number}/reject", [
            'reason' => 'Suspicious activity',
        ])
        ->assertOk();

    $transaction->refresh();
    $account->refresh();

    expect($transaction->status)->toBe(TransactionStatusEnum::REJECTED->value)
        ->and((float)$account->balance)->toBe(3000.0);
});
