<?php


namespace App\Domains\Notification\Data;

use App\Domains\Notification\Enums\AccountActivityTypeEnum;
use Spatie\LaravelData\Data;

class AccountActivityCreateData extends Data
{
    public function __construct(
        public AccountActivityTypeEnum $type,
        public string $account_reference,
      //  public int    $user_reference,
        public ?float $amount = null,
        public ?array $meta = null,
    )
    {
    }
}
