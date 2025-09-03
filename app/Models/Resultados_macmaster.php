<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultadoMcMaster extends Model
{
    protected $table = 'resultado_mcmaster';

    protected $fillable = [
        'resultado_id',
        'codigo_interno',
        'cantidad_muestra',
        'solucion_flotacion',
        'strongylida_c1',
        'strongylida_c2',
        'strongylus_c1',
        'strongylus_c2',
        'moniezia_c1',
        'moniezia_c2',
        'eimeria_c1',
        'eimeria_c2',
        'observaciones',
    ];

    public function resultado()
    {
        return $this->belongsTo(Resultado::class, 'resultado_id');
    }
}
