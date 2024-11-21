<?php

namespace Database\Seeders;

use App\Models\Settings;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Master',
            'email' => 'master@master.com',
            'password' => Hash::make('master@master.com'),
            'role' => 'master',
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin@admin.com'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Usuário Comum',
            'email' => 'user@user.com',
            'password' => Hash::make('user@user.com'),
            'role' => 'user',
        ]);

        Settings::create([
            'background_color' => '#E6E6E6'
        ]);
    }
}
