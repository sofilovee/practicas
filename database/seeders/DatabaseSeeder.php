<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'sofilove804@gmail.com',
            'password' => Hash::make('123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Empleado',
            'email' => 'empleado@correo.com',
            'password' => Hash::make('1234'),
            'role' => 'empleado',
        ]);
    }
}