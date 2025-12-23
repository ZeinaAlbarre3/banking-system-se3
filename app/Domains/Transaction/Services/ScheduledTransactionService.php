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
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ScheduledTransactionService
{
    public function __construct(
        private readonly ScheduledTransactionRepositoryInterface $scheduledTransaction,
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
            'next_run_at' => $this->calculateFirstRun($data),
        ]);
    }


    public function listMySchedules(): Collection
    {
        return $this->scheduledTransaction->forUser(Auth::id());
    }

    public function changeStatus(ScheduledTransaction $scheduled): ScheduledTransaction
    {
        $newStatus = $scheduled->status === SCH::ACTIVE->value
            ? SCH::PAUSED->value
            : SCH::ACTIVE->value;

        return $this->scheduledTransaction->update($scheduled, [
            'status' => $newStatus,
        ]);
    }

    public function delete(ScheduledTransaction $scheduled): void
    {
        $this->scheduledTransaction->delete($scheduled);
    }

    private function calculateFirstRun(ScheduledTransactionCreateData $data): Carbon
    {
        $tz = $data->timezone ?? config('app.timezone', 'UTC');

        $now = now($tz);

        [$hour, $minute] = array_map(
            'intval',
            explode(':', $data->time_of_day ?? '09:00')
        );

        return match ($data->frequency) {

            'daily' => $this->firstDailyRun($now, $hour, $minute),

            'weekly' => $this->firstWeeklyRun(
                $now,
                $data->day_of_week,
                $hour,
                $minute
            ),

            'monthly' => $this->firstMonthlyRun(
                $now,
                $data->day_of_month,
                $hour,
                $minute
            ),

            default => $now->copy()->addDay()->setTime($hour, $minute)->startOfMinute(),
        };
    }

    private function firstDailyRun(Carbon $now, int $hour, int $minute): Carbon
    {
        $candidate = $now->copy()
            ->setTime($hour, $minute)
            ->startOfMinute();

        if ($candidate->lessThanOrEqualTo($now)) {
            $candidate->addDay();
        }

        return $candidate;
    }

    private function firstWeeklyRun(Carbon $now, ?int $dayOfWeek, int $hour, int $minute): Carbon
    {
        $targetDay = $dayOfWeek ?? $now->dayOfWeekIso;

        $candidate = $now->copy()
            ->setISODate(
                $now->year,
                $now->weekOfYear,
                $targetDay
            )
            ->setTime($hour, $minute)
            ->startOfMinute();

        if ($candidate->lessThanOrEqualTo($now)) {
            $candidate->addWeek();
        }

        return $candidate;
    }

    private function firstMonthlyRun(Carbon $now, ?int $dayOfMonth, int $hour, int $minute): Carbon
    {
        $day = max(1, min(28, (int) ($dayOfMonth ?? $now->day)));

        $candidate = $now->copy()
            ->day($day)
            ->setTime($hour, $minute)
            ->startOfMinute();

        if ($candidate->lessThanOrEqualTo($now)) {
            $candidate->addMonth()->day($day);
        }

        return $candidate;
    }

}
