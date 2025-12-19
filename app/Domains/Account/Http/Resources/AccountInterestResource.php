<?php

namespace App\Domains\Account\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class AccountInterestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'account_reference' => $this->account_reference,
            'type'              => $this->type,
            'market_rate'       => $this->market_rate,
            'days'              => $this->days,
            'interest'          => $this->interest,
        ];
    }
}
