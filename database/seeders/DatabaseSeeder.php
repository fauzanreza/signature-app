<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'role' => 'director', // Assuming director role for admin
            'password' => \Illuminate\Support\Facades\Hash::make('admin$123'),
        ]);
    }
}
