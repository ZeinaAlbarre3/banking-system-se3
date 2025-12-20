<?php

namespace App\Domains\Dashboard\Services;

use App\Domains\Dashboard\Repositories\DashboardRepositoryInterface;

class DashboardService
{
    public function __construct(
        protected DashboardRepositoryInterface $repository
    ) {}

    public function getDashboardData(): array
    {
        return [
            'users' => $this->repository->usersStats(),
            'accounts' => $this->repository->accountsStats(),
            'transactions' => $this->repository->transactionsStats(),
            'monitoring' => $this->repository->monitoringStats(),
        ];
    }

    public function getUsersStats(): array
    {
        return $this->repository->usersStats();
    }

    public function getAccountsStats(): array
    {
        return $this->repository->accountsStats();
    }

    public function getTransactionsStats(): array
    {
        return $this->repository->transactionsStats();
    }

    public function getMonitoringStats(): array
    {
        return $this->repository->monitoringStats();
    }
}
