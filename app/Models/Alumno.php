<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $fillable = ['user_id', 'faltas', 'calificacion'];

    protected $casts = [
        'calificacion' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function getEquivalenciaAttribute()
    {
        if ($this->calificacion < 7) {
            return 'NA';
        }
        if ($this->calificacion < 8.5) {
            return 'SA';
        }
        if ($this->calificacion < 9.5) {
            return 'DE';
        }
        return 'AU';
    }
}