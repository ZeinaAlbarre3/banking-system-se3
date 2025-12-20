<?php

namespace App\Domains\Dashboard\Repositories;

interface DashboardRepositoryInterface
{
    public function usersStats(): array;
    public function accountsStats(): array;
    public function transactionsStats(): array;
    public function monitoringStats(): array;
}
