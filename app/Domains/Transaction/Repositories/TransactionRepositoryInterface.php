<?php

namespace App\Domains\Transaction\Repositories;

use App\Domains\Transaction\Models\Transaction;
use Illuminate\Support\Collection;

interface TransactionRepositoryInterface
{
    public function find(int $id): ?Transaction;
    public function findOrFail(int $id): Transaction;
    public function create(array $data): Transaction;
    public function update(Transaction $transaction, array $data): Transaction;
    public function all(): Collection;
}
