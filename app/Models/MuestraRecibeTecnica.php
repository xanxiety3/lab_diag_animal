<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MuestraRecibeTecnica extends Model
{
    protected $table = 'muestra_recibe_tecnica';


protected $fillable = [
        'muestra_enviada_id',
        'tecnica_id',
        'cantidad',
    ];

    public function resultados()
{
    return $this->hasMany(Resultado::class, 'muestra_recibe_tecnica_id');
}

}
