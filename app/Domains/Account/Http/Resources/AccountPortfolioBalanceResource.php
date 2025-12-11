<?php

namespace App\Domains\Account\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class AccountPortfolioBalanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'balance' => (float)$this->resource,
        ];
    }
}
