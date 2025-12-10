<?php

namespace App\Domains\Account\Data;

use App\Domains\Account\Enums\AccountTypeEnum;
use Spatie\LaravelData\Data;

class AccountUpdateData extends Data
{
    public function __construct(
        public ?AccountTypeEnum $type,
        public ?int $parent_id,
        public ?array $metadata,
    ) {}

    public function toFilteredArray(): array
    {
        return array_filter([
            'type'      => $this->type,
            'parent_id' => $this->parent_id,
            'metadata'  => $this->metadata,
        ], fn ($value) => ! is_null($value));
    }
}
