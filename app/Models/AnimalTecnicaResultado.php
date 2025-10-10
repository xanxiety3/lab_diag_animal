<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalTecnicaResultado extends Model
{
    protected $table = 'animal_tecnica_resultado';
    protected $fillable = [
        'animal_id',
        'tecnica_id',
        'remision_muestra_recibe_id'
    ];


public function animal()
{
    return $this->belongsTo(Animale::class, 'animal_id', 'id');
}

}
