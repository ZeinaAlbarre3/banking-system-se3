<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'register',
            'login',
            'logout',
            'view-accounts',
            'view-my-accounts',
            'view-account',
            'create-account',
            'update-account',
            'view-portfolio-balance',
            'create-transaction',
            'view-transactions',
            'view-transaction'
        ];

        $totalPermissionsCount = count($permissions);

        $this->command->newLine();
        $this->command->comment("\tSeeding {$totalPermissionsCount} permissions ...");

        $barPermissions = $this->command->getOutput()->createProgressBar($totalPermissionsCount);
        $barPermissions->setFormat("\t[%bar%] %percent:3s%% (%current%/%max%) - %message% \n");
        $barPermissions->start();

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission]);
            $barPermissions->advance();
        }
        $barPermissions->finish();
        $this->command->info("\n\t✓ All permissions seeded successfully.");
    }
}
