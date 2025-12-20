<?php

namespace App\Domains\Dashboard\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'users' => $this['users'],
            'accounts' => $this['accounts'],
            'transactions' => $this['transactions'],
            'monitoring' => [
                'last_user' => optional($this['monitoring']['last_user'])->only(['id','name','email','created_at']),
                'last_transaction' => optional($this['monitoring']['last_transaction'])->only(['id','amount','status','created_at']),
                'last_audit_log' => optional($this['monitoring']['last_audit_log'])->only(['action','created_at']),
            ],
        ];
    }
}
