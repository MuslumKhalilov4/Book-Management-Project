<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(100)->create();

        $admin_role_id = Role::where('title', 'admin')->first()->id;

        User::create([
            'name' => 'I am Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('Admin123'),
            'role_id' => $admin_role_id
        ]);

        $super_admin_role_id = Role::where('title', 'super_admin')->first()->id;

        User::create([
            'name' => 'I am Super Admin',
            'email' => 'super_admin@example.com',
            'password' => Hash::make('Superadmin123'),
            'role_id' => $super_admin_role_id
        ]);
    }
}
