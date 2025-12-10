<?php

namespace Database\Seeders;

use App\Domains\Auth\Models\User;
use Illuminate\Database\Seeder;

class UserRoleAssignerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->comment("\tAssigning roles to default users...");

        $usersWithRoles = [
            'admin@gmail.com' => 'admin',
            'customer@gmail.com' => 'customer',
            'staff@gmail.com' => 'staff',
        ];

        foreach ($usersWithRoles as $email => $roleName) {
            $user = User::where('email', $email)->first();

            if ($user) {
                $user->syncRoles([$roleName]);
                $this->command->info("\t ✓ Role '{$roleName}' assigned to: {$email}");
            } else {
                $this->command->warn("\t ✗ User with email {$email} not found. Skipping assignment.");
            }
        }
        $this->command->newLine();
        $this->command->comment("\tRoles assignment process completed.");
    }
}
