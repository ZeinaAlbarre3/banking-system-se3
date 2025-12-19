<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionAssignerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->comment("\tAssigning permissions to roles...");

        $rolesWithPermissions = [
            'admin' => [
                'login',
                'logout',
                'create-account',
                'update-account',
                'view-account',
                'view-accounts',
                'approve-transaction',
                'reject-transaction',
            ],
            'manager' => [
                'login',
                'logout',
                'create-account',
                'update-account',
                'view-account',
                'view-accounts',
                'approve-transaction',
                'reject-transaction',
            ],
            'customer' => [
                'register',
                'login',
                'logout',
                'create-account',
                'update-account',
                'view-my-accounts',
                'view-portfolio-balance',
                'view-account',
                'create-transaction',
                'view-transactions',
                'view-transaction'
            ],
            'staff' => [
                'login',
                'logout',
                'create-account',
                'update-account',
                'view-account',
                'view-accounts',
            ],

        ];

        $totalRoles = count($rolesWithPermissions);
        $barRoles = $this->command->getOutput()->createProgressBar($totalRoles);
        $barRoles->setFormat("\t[%bar%] %percent:3s%% - Assigning permissions to: %message%");
        $barRoles->start();

        foreach ($rolesWithPermissions as $roleName => $permissionsToAssign) {
            $barRoles->setMessage("{$roleName} role");
            $role = Role::firstOrCreate(['name' => $roleName]);
            if (in_array('*', $permissionsToAssign)) {
                $role->syncPermissions(Permission::all());
            } else {
                $role->syncPermissions($permissionsToAssign);
            }
            $barRoles->advance();
        }
        $barRoles->finish();
        $this->command->info("\n\t✓ Permissions assigned to roles successfully.");
    }
}
