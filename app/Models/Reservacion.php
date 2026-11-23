<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservacion extends Model
{
    protected $table = 'reservaciones';

    protected $fillable = ['user_id', 'equipo_id', 'estado'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }
}