<?php

namespace App\Domains\Account\Data;

use App\Domains\Account\Enums\AccountStateEnum;
use App\Domains\Account\Enums\AccountTypeEnum;
use App\Domains\Account\Models\Account;
use Spatie\LaravelData\Data;

class AccountData extends Data
{
    public function __construct(
        public int $id,
        public int $user_id,
        public AccountTypeEnum $type,
        public AccountStateEnum $state,
        public float $balance,
        public ?int $parent_id,
        public ?array $metadata,
        public ?string $created_at,
        public ?string $updated_at,
    ) {}

    public static function fromModel(Account $account): self
    {
        return new self(
            id:        $account->id,
            user_id:   $account->user_id,
            type:      $account->type,
            state:     $account->state,
            balance:   (float) $account->balance,
            parent_id: $account->parent_id,
            metadata:  $account->metadata,
            created_at:$account->created_at?->toISOString(),
            updated_at:$account->updated_at?->toISOString(),
        );
    }
}
