<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ── 1. Buat Permissions ────────────────────────────────────────
        $permissions = [
            'user.view',
            'user.create',
            'user.edit',
            'user.delete',
            'role.view',
            'role.create',
            'role.edit',
            'role.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ── 2. Buat Role & assign Permission ──────────────────────────
        $kepalaBidang = Role::firstOrCreate(['name' => 'kepala bidang']);
        $kepalaBidang->syncPermissions(Permission::all());

        $sekretaris = Role::firstOrCreate(['name' => 'sekretaris']);
        $sekretaris->syncPermissions([
            'user.view',
            'user.create',
            'user.edit',
        ]);

        $staff = Role::firstOrCreate(['name' => 'staff']);
        $staff->syncPermissions([
            'user.view',
        ]);

        // ── 3. Buat User Default (Kepala Bidang / Super Admin) ─────────
        $adminUser = User::firstOrCreate(
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'nip' => '000000000',
                'password' => Hash::make('1234567890'),
            ]
        );

        // Assign role kepala bidang ke user admin
        $adminUser->syncRoles(['kepala bidang']);

        $this->command->info(' Role & Permission berhasil dibuat.');
        $this->command->info('User default:');
        $this->command->table(
            ['Field', 'Value'],
            [
                ['Username', 'admin'],
                ['Password', '1234567890'],
                ['Role',     'kepala bidang'],
            ]
        );
        $this->command->warn('⚠️  Segera ganti password setelah login pertama!');
    }
}
