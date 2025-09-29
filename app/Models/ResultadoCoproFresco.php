<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultadoCoproFresco extends Model
{
    protected $table = 'resultados_copro_frescos';

    protected $fillable = [
        'resultado_id',
        'codigo_interno',
        'sexo',
        'especie',
        'color',
        'consistencia',
        'moco',
        'sangre',
        'celulas_epiteliales',
        'celulas_vegetales',
        'huevos',
        'quistes',
        'levaduras',
        'otros',
        'observaciones',
    ];

    public function resultado()
    {
        return $this->belongsTo(Resultado::class, 'resultado_id');
    }
}
