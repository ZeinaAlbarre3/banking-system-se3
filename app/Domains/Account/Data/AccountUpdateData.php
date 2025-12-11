<?php

namespace App\Domains\Account\Data;

use App\Domains\Account\Enums\AccountTypeEnum;
use Spatie\LaravelData\Data;

class AccountUpdateData extends Data
{
    public function __construct(
        public ?string $name,
        public ?AccountTypeEnum $type,
        public ?string $parent_reference,
        public ?array $metadata,
    ) {}

    public function toFilteredArray(): array
    {
        return array_filter([
            'name'      => $this->name,
            'type'      => $this->type,
            'metadata'  => $this->metadata,
        ], fn ($value) => ! is_null($value));
    }
}
