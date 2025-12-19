<?php

namespace Database\Factories;

use App\Domains\Account\Enums\AccountStateEnum;
use App\Domains\Account\Enums\AccountTypeEnum;
use App\Domains\Account\Models\Account;
use App\Domains\Auth\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    protected $model = Account::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'reference_number' => 'ACC-' . Str::upper(Str::random(10)),
            'name' => $this->faker->words(2, true),
            'type' => AccountTypeEnum::CURRENT->value,
            'state' => AccountStateEnum::ACTIVE->value,
            'balance' => 0,
            'parent_id' => null,
            'metadata' => [],
        ];
    }
}
