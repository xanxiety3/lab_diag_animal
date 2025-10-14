<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RemisionRecibeCriterio extends Model
{
    protected $table = 'remision_recibe_criterios';
    protected $fillable = [
        'remision_muestra_recibe_id',
        'criterio_id',
        'si',
        'no',
        'temperatura',
        'observaciones',
    ];

    public $timestamps = true;

    public function remisionRecibe()
    {
        return $this->belongsTo(RemisionMuestraRecibe::class, 'remision_muestra_recibe_id');
    }

    public function criterio()
    {
        return $this->belongsTo(CriteriosAceptacion::class, 'criterio_id');
    }
}
