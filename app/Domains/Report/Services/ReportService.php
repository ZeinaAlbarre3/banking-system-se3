<?php

namespace App\Domains\Report\Services;

use App\Domains\Report\Data\TransactionReportFilterData;
use App\Domains\Report\Repositories\ReportRepositoryInterface;

class ReportService
{
    public function __construct(
        protected ReportRepositoryInterface $repository
    ) {}

    public function transactionReport(TransactionReportFilterData $filters): array
    {
        return $this->repository->transactionReport($filters);
    }
}
