<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            User::PERMISSION_VIEW_ALL_DATA => 'View all data across all lembaga',
            User::PERMISSION_MANAGE_SANTRI => 'Manage santri data',
            User::PERMISSION_MANAGE_ALUMNI => 'Manage alumni data',
            User::PERMISSION_MANAGE_LEMBAGA => 'Manage lembaga data',
            User::PERMISSION_VIEW_REPORTS => 'View reports and statistics',
            User::PERMISSION_EXPORT_DATA => 'Export data to CSV/Excel',
        ];

        foreach ($permissions as $permission => $description) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Create roles and assign permissions
        $superAdminRole = Role::create(['name' => User::ROLE_SUPER_ADMIN]);
        $wilayahRole = Role::create(['name' => User::ROLE_WILAYAH]);
        $sekolahRole = Role::create(['name' => User::ROLE_SEKOLAH]);

        // Super Admin permissions (can view all data but cannot modify lembaga data)
        $superAdminRole->givePermissionTo([
            User::PERMISSION_VIEW_ALL_DATA,
            User::PERMISSION_VIEW_REPORTS,
            User::PERMISSION_EXPORT_DATA,
        ]);

        // Wilayah permissions (can view regional data and reports)
        $wilayahRole->givePermissionTo([
            User::PERMISSION_VIEW_REPORTS,
            User::PERMISSION_EXPORT_DATA,
        ]);

        // Sekolah permissions (can manage their own lembaga data)
        $sekolahRole->givePermissionTo([
            User::PERMISSION_MANAGE_SANTRI,
            User::PERMISSION_MANAGE_ALUMNI,
            User::PERMISSION_MANAGE_LEMBAGA,
            User::PERMISSION_VIEW_REPORTS,
            User::PERMISSION_EXPORT_DATA,
        ]);
    }
}