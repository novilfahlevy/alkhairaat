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
            User::PERMISSION_ACCESS_LEMBAGA => 'Mengakses data Lembaga',
            User::PERMISSION_MANAGE_LEMBAGA => 'Mengelola data Lembaga',
            User::PERMISSION_ACCESS_SANTRI => 'Mengakses data Santri',
            User::PERMISSION_MANAGE_SANTRI => 'Mengelola data Santri',
            User::PERMISSION_ACCESS_ALUMNI => 'Mengakses data Alumni',
            User::PERMISSION_MANAGE_ALUMNI => 'Mengelola data Alumni',
            User::PERMISSION_VIEW_NATIONAL_REPORTS => 'Melihat laporan nasional',
            User::PERMISSION_VIEW_PROVINCE_REPORTS => 'Melihat laporan provinsi',
            User::PERMISSION_VIEW_CITY_REPORTS => 'Melihat laporan kota/kabupaten',
            User::PERMISSION_VIEW_SEKOLAH_REPORTS => 'Melihat laporan sekolah',
            User::PERMISSION_EXPORT_DATA => 'Export data',
            User::PERMISSION_MANAGE_USER_SEKOLAH => 'Mengelola akun user sekolah',
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

        // Super Admin permissions (dapat melihat semua laporan)
        $superAdminRole->givePermissionTo([
            User::PERMISSION_ACCESS_LEMBAGA,
            User::PERMISSION_MANAGE_LEMBAGA,
            User::PERMISSION_ACCESS_SANTRI,
            User::PERMISSION_MANAGE_SANTRI,
            User::PERMISSION_ACCESS_ALUMNI,
            User::PERMISSION_MANAGE_ALUMNI,
            User::PERMISSION_VIEW_NATIONAL_REPORTS,
            User::PERMISSION_VIEW_PROVINCE_REPORTS,
            User::PERMISSION_VIEW_CITY_REPORTS,
            User::PERMISSION_VIEW_SEKOLAH_REPORTS,
            User::PERMISSION_EXPORT_DATA,
            User::PERMISSION_MANAGE_USER_SEKOLAH,
        ]);

        // Wilayah permissions (dapat melihat laporan provinsi dan kota/kabupaten)
        $wilayahRole->givePermissionTo([
            User::PERMISSION_VIEW_PROVINCE_REPORTS,
            User::PERMISSION_VIEW_CITY_REPORTS,
            User::PERMISSION_EXPORT_DATA,
            User::PERMISSION_MANAGE_USER_SEKOLAH,
        ]);

        // Sekolah permissions (dapat melihat laporan sekolah mereka sendiri)
        $sekolahRole->givePermissionTo([
            User::PERMISSION_ACCESS_SANTRI,
            User::PERMISSION_MANAGE_SANTRI,
            User::PERMISSION_ACCESS_ALUMNI,
            User::PERMISSION_MANAGE_ALUMNI,
            User::PERMISSION_VIEW_SEKOLAH_REPORTS,
            User::PERMISSION_EXPORT_DATA,
        ]);
    }
}