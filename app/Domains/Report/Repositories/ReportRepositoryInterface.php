<?php

namespace App\Domains\Report\Repositories;

use App\Domains\Report\Data\TransactionReportFilterData;

interface ReportRepositoryInterface
{
    public function transactionReport(TransactionReportFilterData $filters): array;
}
