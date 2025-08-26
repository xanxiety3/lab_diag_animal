<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RemisionMuestraRecibe
 * 
 * @property int $id
 * @property int $muestra_enviada_id
 * @property int $responsable_id
 * @property Carbon|null $fecha

 * 
 * @property RemisionMuestraEnvio $remision_muestra_envio
 * @property Persona $persona

 *
 * @package App\Models
 */
class RemisionMuestraRecibe extends Model
{
	protected $table = 'remision_muestra_recibe';
	public $timestamps = false;

	protected $casts = [
		'muestra_enviada_id' => 'int',
		'responsable_id' => 'int',
		'fecha' => 'datetime',

	];

	protected $fillable = [
		'muestra_enviada_id',
		'responsable_id',
		'fecha',
		'registro_resultado',
		'rechazada'

	];

	public function resultados()
	{
		return $this->hasMany(Resultado::class, 'remision_muestra_recibe_id');
	}


	public function remision_muestra_envio()
	{
		return $this->belongsTo(RemisionMuestraEnvio::class, 'muestra_enviada_id');
	}

	public function persona()
	{
		return $this->belongsTo(Persona::class, 'responsable_id');
	}
	public function tecnicas()
	{
		return $this->belongsToMany(TecnicasMuestra::class, 'muestra_recibe_tecnica', 'muestra_recibe_id', 'tecnica_id')->withTimestamps();
	}

	public function responsable()
{
    return $this->belongsTo(User::class, 'responsable_id');
}



	// Scope para filtrar por resultado
    public function scopeResultado($query, $valor)
    {
        if ($valor === 'con') {
            return $query->where('registro_resultado', true);
        } elseif ($valor === 'sin') {
            return $query->where('registro_resultado', false);
        }
        return $query; // 'todos' o valor no reconocido
    }

    // Scope para filtrar por estado (rechazada o aceptada)
    public function scopeEstado($query, $valor)
    {
        if ($valor === 'aceptadas') {
            return $query->where('rechazada', false);
        } elseif ($valor === 'rechazadas') {
            return $query->where('rechazada', true);
        }
        return $query; // 'todos' o valor no reconocido
    }
}

