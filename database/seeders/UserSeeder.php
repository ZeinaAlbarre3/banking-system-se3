<?php

namespace Database\Seeders;

use App\Domains\Auth\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->comment("\tSeeding default users...");

        $defaultUsers = [
            [
                'reference_number' => 'US-000001',
                'name' => 'Admin Account',
                'email' => 'admin@gmail.com',
                'password' => 'password',
            ],
            [
                'reference_number' => 'US-000002',
                'name' => 'Customer Account',
                'email' => 'customer@gmail.com',
                'password' => 'password',
            ],
            [
                'reference_number' => 'US-000003',
                'name' => 'Staff Account',
                'email' => 'staff@gmail.com',
                'password' => 'password',
            ],
            [
                'reference_number' => 'US-000004',
                'name' => 'Manager Account',
                'email' => 'manager@gmail.com',
                'password' => 'password',
            ]
        ];


        foreach ($defaultUsers as $userData) {
            User::updateOrCreate(
                ['reference_number' => $userData['reference_number'],'email' => $userData['email']],
                [
                    'name'     => $userData['name'],
                    'password' => Hash::make($userData['password']),
                    'email_verified_at' => now(),
                ]
            );
            $this->command->info("\t ✓ User seeded successfully.");
        }

        $this->command->comment("\n\t All users seeded successfully!");
    }
}
