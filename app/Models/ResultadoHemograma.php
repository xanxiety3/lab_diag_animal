<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultadoHemograma extends Model
{
    protected $table = 'resultados_hemogramas';

    protected $fillable = [
        'resultado_id',
        'codigo_interno',
        'especie',
        'sexo',
        'hb',
        'hto',
        'leucocitos',
        'neu',
        'eos',
        'bas',
        'lin',
        'mon',
        'plaquetas',
        'vcm',
        'hcm',
        'chcm',
        'hemoparasitos',
        'observaciones',
    ];

    public function resultado()
    {
        return $this->belongsTo(Resultado::class, 'resultado_id');
    }
}
