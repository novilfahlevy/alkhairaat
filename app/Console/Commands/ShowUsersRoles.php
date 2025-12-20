<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ShowUsersRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:show-roles {--detail : Show detailed permission information}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display all users with their roles and permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔐 Alkhairaat Users, Roles & Permissions Overview');
        $this->newLine();

        // Show all users
        $users = User::with('roles')->get();

        $this->table(
            ['ID', 'Name', 'Email', 'Legacy Role', 'Spatie Roles', 'Sekolah ID'],
            $users->map(function ($user) {
                return [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role ?? 'N/A',
                    $user->getRoleNames()->implode(', ') ?: 'No roles',
                    $user->sekolah_id ?? 'N/A'
                ];
            })
        );

        if ($this->option('detail')) {
            $this->newLine();
            $this->info('📋 Available Roles and Their Permissions:');
            $this->newLine();

            $roles = Role::with('permissions')->get();
            
            foreach ($roles as $role) {
                $this->line("<fg=yellow>🏷️  Role: {$role->name}</>");
                
                if ($role->permissions->count() > 0) {
                    $permissions = $role->permissions->pluck('name')->map(function($permission) {
                        return "   ✓ {$permission}";
                    });
                    $this->line($permissions->implode("\n"));
                } else {
                    $this->line('   No permissions assigned');
                }
                $this->newLine();
            }

            $this->newLine();
            $this->info('🔑 Available Permissions:');
            Permission::all()->each(function($permission) {
                $this->line("• {$permission->name}");
            });
        }

        $this->newLine();
        $this->info('✅ Role and permission system is working with Spatie Laravel Permission!');
    }
}
