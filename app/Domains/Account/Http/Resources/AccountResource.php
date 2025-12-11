<?php

namespace App\Domains\Account\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class AccountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'reference_number' => $this->reference_number,
            'type' => $this->type,
            'state' => $this->state,
            'balance' => (float)$this->balance,
            'parent_id' => $this->parent_id,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
