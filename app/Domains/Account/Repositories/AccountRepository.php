<?php

namespace App\Domains\Account\Repositories;

use App\Domains\Account\Models\Account;
use Illuminate\Support\Collection;

class AccountRepository implements AccountRepositoryInterface
{
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
