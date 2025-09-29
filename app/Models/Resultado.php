<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resultado extends Model
{
    protected $fillable = [

        'usuario_id',
        'estado',
        'muestra_recibe_tecnica_id',
        'animal_id',

    ];

    // Relación con remisión
    public function muestraRecibida()
    {
        return $this->belongsTo(RemisionMuestraRecibe::class, 'remision_muestra_recibe_id');
    }



    // Relación con usuario (veterinario)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relación con técnica de muestra
    public function tecnicaMuestra()
    {
        return $this->belongsTo(TecnicasMuestra::class, 'tecnica_muestra_id');
    }

    // Relaciones con derivadas
    public function mcmaster()
    {
        return $this->hasOne(ResultadoMcMaster::class, 'resultado_id');
    }

    public function bearman()
    {
        return $this->hasOne(ResultadoBearman::class, 'resultado_id');
    }

    public function hemograma()
    {
        return $this->hasOne(ResultadoHemograma::class, 'resultado_id');
    }

    public function coproFresco()
    {
        return $this->hasOne(ResultadoCoproFresco::class, 'resultado_id');
    }
}
