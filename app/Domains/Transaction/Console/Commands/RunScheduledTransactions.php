<?php

namespace App\Domains\Transaction\Console\Commands;

use App\Domains\Transaction\Models\ScheduledTransaction;
use App\Domains\Transaction\Services\TransactionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RunScheduledTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:run-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute scheduled transactions';

    /**
     * Execute the console command.
     */
    public function handle(TransactionService $transactions): void
    {
        $now = now();

        $items = ScheduledTransaction::query()
            ->where('status', 'active')
            ->where('next_run_at', '<=', $now)
            ->get();

        foreach ($items as $scheduled) {
            DB::transaction(function () use ($scheduled, $transactions) {
                try {
                    $transactions->createTransactionFromScheduled($scheduled);

                    $scheduled->update([
                        'last_run_at' => now(),
                        'runs_count' => $scheduled->runs_count + 1,
                        'next_run_at' => $this->calculateNextRun($scheduled),
                        'last_error' => null,
                    ]);
                } catch (\Throwable $e) {
                    $scheduled->update([
                        'last_error' => $e->getMessage(),
                    ]);
                }
            });
        }
    }

    public function calculateNextRun(ScheduledTransaction $s): \Carbon\Carbon
    {
        $tz = $s->timezone ?: config('app.timezone', 'UTC');

        $now = now($tz);

        [$hour, $minute] = array_map('intval', explode(':', $s->time_of_day ?? '09:00'));

        return match ($s->frequency) {

            'daily' => $now
                ->copy()
                ->setTime($hour, $minute)
                ->addDay()
                ->startOfMinute(),

            'weekly' => $this->nextWeeklyRun($now, $s->day_of_week, $hour, $minute),

            'monthly' => $this->nextMonthlyRun($now, $s->day_of_month, $hour, $minute),

            default => $now->copy()->addDay()->setTime($hour, $minute)->startOfMinute(),
        };
    }

    private function nextWeeklyRun(\Carbon\Carbon $now, ?int $dayOfWeek, int $hour, int $minute): \Carbon\Carbon
    {
        $target = $dayOfWeek ?? $now->dayOfWeekIso; // 1..7

        $candidate = $now->copy()
            ->setISODate($now->year, $now->weekOfYear, $target)
            ->setTime($hour, $minute)
            ->startOfMinute();

        if ($candidate->lessThanOrEqualTo($now)) {
            $candidate->addWeek();
        }

        return $candidate;
    }

    private function nextMonthlyRun(\Carbon\Carbon $now, ?int $dayOfMonth, int $hour, int $minute): \Carbon\Carbon
    {
        $day = max(1, min(28, (int) ($dayOfMonth ?? $now->day))); // لتفادي 29-31 مشاكل

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
