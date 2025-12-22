<?php

namespace App\Domains\Transaction\Services;

use App\Domains\Account\Models\Account;
use App\Domains\Account\Repositories\AccountRepositoryInterface;
use App\Domains\Transaction\Chains\TransactionValidationChainFactory;
use App\Domains\Transaction\Data\ScheduledTransactionCreateData;
use App\Domains\Transaction\Data\TransactionCreateData;
use App\Domains\Transaction\Enums\ScheduleStatusEnum as SCH;
use App\Domains\Transaction\Models\ScheduledTransaction;
use App\Domains\Transaction\Repositories\TransactionSchedule\ScheduledTransactionRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ScheduledTransactionService
{
    public function __construct(
        private readonly ScheduledTransactionRepositoryInterface $scheduledTransaction,
        private readonly TransactionValidationChainFactory $validationChainFactory,
        private readonly TransactionSharedService $sharedService,
    ) {}

    public function create(ScheduledTransactionCreateData $data): ScheduledTransaction
    {
        [$account, $related] = $this->sharedService->resolveAccounts($data);

        $this->sharedService->validate($account, $data, $related);

        return $this->createScheduleTransaction($account, $related, $data)->refresh();
    }

    public function createScheduleTransaction(Account $account, ?Account $related, ScheduledTransactionCreateData $data): ScheduledTransaction
    {
        return $this->scheduledTransaction->create([
            'user_id' => Auth::id(),
            'account_id' => $account->id,
            'related_account_id' => $related?->id,
            'type' => $data->type->value,
            'amount' => $data->amount,
            'currency' => $data->currency ?? 'USD',
            'metadata' => $data->metadata ?? [],
            'frequency' => $data->frequency,
            'day_of_week' => $data->day_of_week,
            'day_of_month' => $data->day_of_month,
            'time_of_day' => $data->time_of_day,
            'timezone' => $data->timezone,
            'next_run_at' => now(),
        ]);
    }

    public function listMySchedules(): Collection
    {
        return $this->scheduledTransaction->forUser(Auth::id());
    }

    public function changeStatus(ScheduledTransaction $scheduled): ScheduledTransaction
    {
        $this->ensureOwner($scheduled);

        $newStatus = $scheduled->status === SCH::ACTIVE->value
            ? SCH::PAUSED->value
            : SCH::ACTIVE->value;

        return $this->scheduledTransaction->update($scheduled, [
            'status' => $newStatus,
        ]);
    }

    public function delete(ScheduledTransaction $scheduled): void
    {
        $this->ensureOwner($scheduled);

        $this->scheduledTransaction->delete($scheduled);
    }

    private function ensureOwner(ScheduledTransaction $scheduled): void
    {
        if ((int) $scheduled->user_id !== (int) Auth::id()) {
            abort(403, 'Authorization failed.');
        }
    }

    private function validate(Account $account, TransactionCreateData $data, ?Account $related): void
    {
        $this->validationChainFactory->make()->handle($account, $data, $related);
    }
}
