<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         User::updateOrCreate(
            ['email' => 'admin@simpeda.test'],
            ['name' => 'Admin SIMPEDA', 'password' => Hash::make('admin12345'), 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'petugas@simpeda.test'],
            ['name' => 'Petugas Desa', 'password' => Hash::make('petugas12345'), 'role' => 'petugas']
        );
        //
    }
}