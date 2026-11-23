<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    protected $fillable = [
        'jugador_x_id',
        'jugador_o_id',
        'turno',
        'estado',
        'ganador_id',
        'resultado',
    ];

    public function jugadorX()
    {
        return $this->belongsTo(User::class, 'jugador_x_id');
    }

    public function jugadorO()
    {
        return $this->belongsTo(User::class, 'jugador_o_id');
    }

    public function ganador()
    {
        return $this->belongsTo(User::class, 'ganador_id');
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }

    // El tablero se arma con los movimientos: un arreglo de 9 casillas
    public function tablero()
    {
        $tablero = array_fill(0, 9, null);

        foreach ($this->movimientos as $movimiento) {
            $tablero[$movimiento->posicion] = $movimiento->simbolo;
        }

        return $tablero;
    }

    // Devuelve 'X', 'O' o null si el usuario no juega esta partida
    public function simboloDe($userId)
    {
        if ($this->jugador_x_id == $userId) {
            return 'X';
        }
        if ($this->jugador_o_id == $userId) {
            return 'O';
        }
        return null;
    }
}