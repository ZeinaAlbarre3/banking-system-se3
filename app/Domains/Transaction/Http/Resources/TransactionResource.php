<?php

namespace App\Domains\Transaction\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'reference_number'   => $this->reference_number,
            'type'               => $this->type,
            'status'             => $this->status,
            'amount'             => (float) $this->amount,
            'currency'           => $this->currency,
            'account_reference'         => $this->account?->reference_number,
            'related_account_reference' => $this->relatedAccount?->reference_number,
            'metadata'           => $this->metadata,
            'processed_at'       => $this->processed_at,
            'created_by'         => $this->created_by,
            'approved_by'        => $this->approved_by,
            'created_at'         => $this->created_at,
            'updated_at'         => $this->updated_at,
        ];
    }
}
