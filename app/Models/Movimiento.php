<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $fillable = ['partida_id', 'user_id', 'posicion', 'simbolo'];

    public function partida()
    {
        return $this->belongsTo(Partida::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}