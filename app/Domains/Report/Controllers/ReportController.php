<?php

namespace App\Domains\Report\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domains\Report\Services\ReportService;
use App\Domains\Report\Data\TransactionReportFilterData;
use App\Domains\Report\Resources\TransactionReportResource;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function transactions(Request $request, ReportService $service)
    {
        $filters = TransactionReportFilterData::fromRequest($request);

        return self::Success(
            new TransactionReportResource(
                $service->transactionReport($filters)
            )
        );
    }
}
