<?php

namespace App\Domains\Account\Repositories;

use App\Domains\Account\Models\Account;
use Illuminate\Support\Collection;

class AccountRepository implements AccountRepositoryInterface
{
    public function find(int $id): ?Account
    {
        return Account::query()->find($id);
    }

    public function findOrFail(int $id): Account
    {
        return Account::query()->findOrFail($id);
    }
    public function findByReference(string $reference): ?Account
    {
        return Account::query()
            ->where('reference_number', $reference)
            ->first();
    }

    public function findByReferenceOrFail(string $reference): Account
    {
        return Account::query()
            ->where('reference_number', $reference)
            ->firstOrFail();
    }
    public function findByUser(int $userId): Collection
    {
        return Account::query()->where('user_id', $userId)->get();
    }

    public function create(array $data): Account
    {
        return Account::query()->create($data);
    }

    public function update(Account $account, array $data): Account
    {
        $account->update($data);
        return $account;
    }

    public function delete(Account $account): void
    {
        $account->delete();
    }
}
