<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultadoBearman extends Model
{
    protected $table = 'resultados_bearmen';

    protected $fillable = [
        'resultado_id',
        'codigo_interno',
        'codigo_solicitud',
        'fecha_analisis',
        'cantidad_muestra',
        'larvas',
        'observaciones',
    ];

    public function resultado()
    {
        return $this->belongsTo(Resultado::class, 'resultado_id');
    }
}
