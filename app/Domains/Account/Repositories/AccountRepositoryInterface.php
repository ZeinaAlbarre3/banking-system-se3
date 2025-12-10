<?php

namespace App\Domains\Account\Repositories;

use App\Domains\Account\Models\Account;
use Illuminate\Support\Collection;

interface AccountRepositoryInterface
{
    public function findByUser(int $userId): Collection;

    public function create(array $data): Account;

    public function update(Account $account, array $data): Account;

    public function delete(Account $account): void;
}
