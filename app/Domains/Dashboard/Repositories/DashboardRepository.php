<?php

namespace App\Domains\Dashboard\Repositories;

use App\Domains\Account\Models\Account;
use App\Domains\Auth\Models\User;
use App\Domains\Transaction\Models\AuditLog;
use App\Domains\Transaction\Models\Transaction;
use Carbon\Carbon;
use App\Domains\Transaction\Enums\TransactionStatusEnum;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function usersStats(): array
    {
        return [
            'total' => User::query()->count(),
            'today' => User::query()->whereDate('created_at', today())->count(),
            'this_week' => User::query()->whereBetween(
                'created_at',
                [now()->startOfWeek(), now()->endOfWeek()]
            )->count(),
        ];
    }

    public function accountsStats(): array
    {
        return [
            'total' => Account::query()->count(),
            'by_state' => Account::query()->selectRaw('state, COUNT(*) as count')
                ->groupBy('state')
                ->pluck('count', 'state'),
            'total_balance' => Account::query()->sum('balance'),
        ];
    }

    public function transactionsStats(): array
    {
        return [
            'today' => Transaction::query()->whereDate('created_at', today())->count(),
            'this_week' => Transaction::query()->whereBetween(
                'created_at',
                [now()->startOfWeek(), now()->endOfWeek()]
            )->count(),
            'pending' => Transaction::query()->where(
                'status',
                TransactionStatusEnum::PENDING->value
            )->count(),
            'amount_today' => Transaction::query()->whereDate('created_at', today())->sum('amount'),
            'amount_this_week' => Transaction::query()->whereBetween(
                'created_at',
                [now()->startOfWeek(), now()->endOfWeek()]
            )->sum('amount'),
        ];
    }

    public function monitoringStats(): array
    {
        return [
            'last_user' => User::query()->latest()->first(),
            'last_transaction' => Transaction::query()->latest()->first(),
            'last_audit_log' => AuditLog::query()->latest()->first(),
        ];
    }
}
