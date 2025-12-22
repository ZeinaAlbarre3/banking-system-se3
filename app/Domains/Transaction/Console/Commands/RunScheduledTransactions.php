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
                    $transactions->createFromScheduled($scheduled);

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

    private function calculateNextRun(ScheduledTransaction $s): \Carbon\Carbon
    {
        return match ($s->frequency) {
            'daily' => now()->addDay(),
            'weekly' => now()->addWeek(),
            'monthly' => now()->addMonth(),
        };
    }
}
