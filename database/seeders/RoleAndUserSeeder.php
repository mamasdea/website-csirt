<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // ====== 1️⃣ Seed tabel roles ======
        $roles = [
            ['role_name' => 'Superadmin'],
            ['role_name' => 'Admin'],
            ['role_name' => 'User'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['role_name' => $role['role_name']],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        // Ambil ID role Superadmin
        $superadminRoleId = DB::table('roles')->where('role_name', 'Superadmin')->value('id');

        // ====== 2️⃣ Seed user Superadmin ======
        User::updateOrCreate(
            ['email' => 'superadmin@app.com'],
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'is_active' => 1,
                'role_id' => $superadminRoleId,
            ]
        );
    }
}
