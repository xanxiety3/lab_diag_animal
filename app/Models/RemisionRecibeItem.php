<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RemisionRecibeItem extends Model
{
    protected $fillable = [
        'remision_muestra_recibe_id',
        'id_item',
        'tipo_empaque',
        'cantidad_requerida',
        'temperatura',
        'observaciones',
        'aceptado',
        'codigo_interno',
    ];
}
