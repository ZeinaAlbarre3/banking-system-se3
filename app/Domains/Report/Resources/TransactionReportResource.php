<?php

namespace App\Domains\Report\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionReportResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'summary' => [
                'total_count' => $this['total_count'],
                'total_amount' => $this['total_amount'],
            ],
            'grouped_by_type' => $this['by_type'],
            'transactions' => $this['transactions'],
        ];
    }
}
