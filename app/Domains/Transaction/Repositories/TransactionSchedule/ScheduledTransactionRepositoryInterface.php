<?php

namespace App\Domains\Transaction\Repositories\TransactionSchedule;

use App\Domains\Transaction\Models\ScheduledTransaction;
use Illuminate\Support\Collection;

interface ScheduledTransactionRepositoryInterface
{
    public function forUser(int $userId): Collection;
    public function create(array $data): ScheduledTransaction;
    public function findByReferenceOrFail(string $reference): ScheduledTransaction;
    public function delete(ScheduledTransaction $scheduled): void;
    public function update(ScheduledTransaction $scheduled, array $data): ScheduledTransaction;
}
