<?php

namespace Database\Seeders;

use App\Models\Equipo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EquiposSeeder extends Seeder
{
    public function run(): void
    {
        $estudiantes = [
            ['Carlos Mendoza', 'estudiante1@correo.com'],
            ['Laura Vega', 'estudiante2@correo.com'],
        ];

        foreach ($estudiantes as $e) {
            User::create([
                'name' => $e[0],
                'email' => $e[1],
                'password' => Hash::make('12345678'),
                'role' => 'estudiante',
            ]);
        }

        $equipos = [
            ['Laptop HP 01', 'Laptop con 8 GB de RAM y Windows 11'],
            ['Laptop HP 02', 'Laptop con 8 GB de RAM y Windows 11'],
            ['Laptop Dell 01', 'Laptop con 16 GB de RAM para programación'],
            ['PC de escritorio 01', 'Computadora con monitor, teclado y mouse'],
            ['PC de escritorio 02', 'Computadora con monitor, teclado y mouse'],
            ['Tableta Samsung', 'Tableta de 10 pulgadas con lápiz digital'],
            ['Proyector Epson', 'Proyector HDMI de 3000 lúmenes'],
            ['Cámara web externa', 'Cámara web 1080p con trípode'],
        ];

        foreach ($equipos as $e) {
            Equipo::create([
                'nombre' => $e[0],
                'descripcion' => $e[1],
                'estado' => 'disponible',
            ]);
        }
    }
}