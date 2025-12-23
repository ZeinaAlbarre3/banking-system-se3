<?php

namespace Database\Factories;

use App\Domains\Notification\Enums\AccountActivityTypeEnum;
use App\Domains\Notification\Models\AccountActivity;
use Illuminate\Database\Eloquent\Factories\Factory;


class AccountActivityFactory extends Factory
{
    protected $model = AccountActivity::class;

    public function definition(): array
    {
        return [
            'user_id'        => 1,
            'account_id'     => 1,
            'type'           => AccountActivityTypeEnum::BALANCE_CHANGE->value,
            'amount'         => 100,
            'balance_before' => 1000,
            'balance_after'  => 1100,
            'meta'           => [],
        ];
    }
}
