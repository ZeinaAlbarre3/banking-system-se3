<?php

namespace App\Domains\Report\Repositories;

use App\Domains\Transaction\Models\Transaction;
use App\Domains\Report\Data\TransactionReportFilterData;

class ReportRepository implements ReportRepositoryInterface
{
    public function transactionReport(TransactionReportFilterData $filters): array
    {
        $query = Transaction::query();

        $query->when($filters->date_from, fn ($q) =>
        $q->whereDate('created_at', '>=', $filters->date_from)
        );

        $query->when($filters->date_to, fn ($q) =>
        $q->whereDate('created_at', '<=', $filters->date_to)
        );

        $query->when($filters->type, fn ($q) =>
        $q->where('type', $filters->type)
        );

        $query->when($filters->status, fn ($q) =>
        $q->where('status', $filters->status)
        );

        $query->when($filters->account_id, fn ($q) =>
        $q->where('account_id', $filters->account_id)
        );

        return [
            'total_count' => (clone $query)->count(),
            'total_amount' => (clone $query)->sum('amount'),

            'by_type' => (clone $query)
                ->selectRaw('type, COUNT(*) as count, SUM(amount) as total_amount')
                ->groupBy('type')
                ->get(),

            'transactions' => $query
                ->latest()
                ->paginate(20),
        ];
    }
}
