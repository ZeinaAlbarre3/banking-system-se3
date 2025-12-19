<?php

namespace Database\Factories;

use App\Domains\Account\Enums\AccountStateEnum;
use App\Domains\Account\Enums\AccountTypeEnum;
use App\Domains\Account\Models\Account;
use App\Domains\Auth\Models\User;
use App\Domains\Transaction\Data\TransactionApprovalData;
use App\Domains\Transaction\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'reference_number' => $this->faker->uuid,
            'type' => 'deposit',
            'status' => 'completed',
            'amount' => 100,
            'currency' => 'USD',
        ];
    }
}
