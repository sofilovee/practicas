<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'estado'];

    public function reservaciones()
    {
        return $this->hasMany(Reservacion::class);
    }
}