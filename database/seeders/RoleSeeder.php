<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->comment("\tSeeding default roles...");

        $defaultRoles = [
            'customer',
            'staff',
            'admin',
            'manager',
        ];

        foreach ($defaultRoles as $roleName) {
            Role::updateOrCreate(
                ['name' => $roleName],
            );
            $this->command->info("\t ✓ Role '{$roleName}' seeded successfully.");
        }

        $this->command->newLine();
        $this->command->comment("\tAll default roles seeded successfully!");
    }
}
