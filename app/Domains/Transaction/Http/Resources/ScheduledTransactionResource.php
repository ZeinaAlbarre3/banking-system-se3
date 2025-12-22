<?php

namespace App\Domains\Transaction\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduledTransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'reference_number' => $this->reference_number,
            'status' => $this->status,
            'type' => $this->type,
            'amount' => (float) $this->amount,
            'currency' => $this->currency,
            'metadata' => $this->metadata,
            'account_reference' => $this->account_reference,
            'related_account_reference' => $this->related_account_reference,
            'frequency' => $this->frequency,
            'day_of_week' => $this->day_of_week,
            'day_of_month' => $this->day_of_month,
            'time_of_day' => $this->time_of_day,
            'timezone' => $this->timezone,
            'next_run_at' => $this->next_run_at,
            'last_run_at' => $this->last_run_at,
            'runs_count' => (int) $this->runs_count,
            'last_error' => $this->last_error,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
