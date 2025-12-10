<?php

namespace App\Domains\Account\Data;

use Spatie\LaravelData\Data;

class AccountCreateData extends Data
{
    public function __construct(
        public int $user_id,
        public string $type,
        public ?int $parent_id,
        public ?array $metadata,
    ) {}
}
