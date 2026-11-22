<?php

namespace Database\Seeders;

use App\Models\Alumno;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AlumnosSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Profesor',
            'email' => 'profesor@correo.com',
            'password' => Hash::make('12345678'),
            'role' => 'profesor',
        ]);

        $alumnos = [
            ['Ana López', 'ana@correo.com', 2, 9.6],
            ['Luis Pérez', 'luis@correo.com', 5, 7.5],
            ['Marta Ruiz', 'marta@correo.com', 12, 5.0],
        ];

        foreach ($alumnos as $a) {
            $usuario = User::create([
                'name' => $a[0],
                'email' => $a[1],
                'password' => Hash::make('12345678'),
                'role' => 'alumno',
            ]);

            Alumno::create([
                'user_id' => $usuario->id,
                'faltas' => $a[2],
                'calificacion' => $a[3],
            ]);
        }
    }
}