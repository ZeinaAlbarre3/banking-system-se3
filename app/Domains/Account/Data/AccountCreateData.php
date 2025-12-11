<?php

namespace App\Domains\Account\Data;

use App\Domains\Account\Enums\AccountTypeEnum;
use Spatie\LaravelData\Data;

class AccountCreateData extends Data
{
    public function __construct(
        public string $name,
        public AccountTypeEnum $type,
        public ?string $parent_reference,
        public ?array $metadata,
    ) {}
}
