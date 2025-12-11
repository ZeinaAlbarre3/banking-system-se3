<?php

namespace App\Domains\Transaction\Repositories;

use App\Domains\Transaction\Models\Transaction;
use Illuminate\Support\Collection;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function find(int $id): ?Transaction
    {
        return Transaction::query()->find($id);
    }
    public function findOrFail(int $id): Transaction
    {
        return Transaction::query()->findOrFail($id);
    }

    public function create(array $data): Transaction
    {
        return Transaction::query()->create($data);
    }

    public function all(): Collection
    {
        return Transaction::query()->latest()->get();
    }
}
