<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadmin = \App\Models\User::factory()->create([
            'name' => 'Superadmin',
            'email' => 'superadmin@gmail.com',
        ]);

        $admin = \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
        ]);

        $roleSuperadmin = \Spatie\Permission\Models\Role::where('name', \App\Enums\Roles\Role::Superadmin->value)->first();
        $superadmin->assignRole($roleSuperadmin);

        $roleAdmin = \Spatie\Permission\Models\Role::where('name', \App\Enums\Roles\Role::Admin->value)->first();
        $admin->assignRole($roleAdmin);
    }
}
