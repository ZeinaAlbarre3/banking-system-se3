<?php

namespace App\Domains\Account\Data;

use App\Domains\Account\Enums\AccountStateEnum;
use Spatie\LaravelData\Data;

class AccountStateChangeData extends Data
{
    public function __construct(
        public AccountStateEnum $state
    ) {}
}
