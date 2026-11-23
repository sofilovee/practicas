<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class JugadoresSeeder extends Seeder
{
    public function run(): void
    {
        $jugadores = [
            ['Jugador Uno', 'jugador1@correo.com'],
            ['Jugador Dos', 'jugador2@correo.com'],
            ['Jugador Tres', 'jugador3@correo.com'],
        ];

        foreach ($jugadores as $j) {
            User::create([
                'name' => $j[0],
                'email' => $j[1],
                'password' => Hash::make('12345678'),
                'role' => 'jugador',
            ]);
        }
    }
}