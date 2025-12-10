<?php

namespace App\Domains\Auth\Repositories;


use App\Domains\Auth\Models\User;
use App\Domains\Auth\Data\RegisterUserData;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    /**
     * Create a new user with translatable name.
     */
    public function create(RegisterUserData $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::query()->create([
                ...$data->toArray(),
            ]);

            $user->assignRole('customer');

            return $user;
        });
    }

    public function update(RegisterUserData $data, User $user): User
    {
        return DB::transaction(function () use ($user, $data) {
            $user->update([
                ...$data->toArray(),
            ]);
            return $user;
        });
    }

    public function findByEmail(string $email): User
    {
        return User::query()->where('email', $email)->firstOrFail();
    }

    public function findByEmailOrNull(string $email): ?User
    {
        return User::query()->where('email', $email)->first();
    }


}
