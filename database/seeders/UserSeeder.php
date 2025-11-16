<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@wiki.local',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create editor user
        User::create([
            'name' => 'Editor User',
            'email' => 'editor@wiki.local',
            'password' => Hash::make('password'),
            'role' => 'editor',
        ]);

        // Create viewer user
        User::create([
            'name' => 'Viewer User',
            'email' => 'viewer@wiki.local',
            'password' => Hash::make('password'),
            'role' => 'viewer',
        ]);
    }
}
