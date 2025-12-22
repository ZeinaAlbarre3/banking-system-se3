<?php

namespace App\Domains\Transaction\Repositories\TransactionSchedule;

use App\Domains\Transaction\Models\ScheduledTransaction;
use Illuminate\Support\Collection;

class ScheduledTransactionRepository implements ScheduledTransactionRepositoryInterface
{
    public function forUser(int $userId): Collection
    {
        return ScheduledTransaction::query()
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    public function create(array $data): ScheduledTransaction
    {
        return ScheduledTransaction::query()->create($data);
    }

    public function findByReferenceOrFail(string $reference): ScheduledTransaction
    {
        return ScheduledTransaction::query()
            ->where('reference_number', $reference)
            ->firstOrFail();
    }

    public function delete(ScheduledTransaction $scheduled): void
    {
        $scheduled->delete();
    }

    public function update(ScheduledTransaction $scheduled, array $data): ScheduledTransaction
    {
        $scheduled->fill($data);
        $scheduled->save();

        return $scheduled;
    }

}
