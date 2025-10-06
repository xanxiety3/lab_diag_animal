<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
		'rechazada' => 'boolean',
		'registro_resultado' => 'boolean',


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



	public function tecnicas()
	{
		return $this->belongsToMany(
			TecnicasMuestra::class,         // ✅ modelo destino correcto
			'muestra_recibe_tecnica',      // tabla pivot
			'muestra_recibe_id',           // FK en pivot hacia este modelo
			'tecnica_id'                   // FK en pivot hacia tecnicas_muestra
		)->withPivot('cantidad'); 
	}

	public function animales()
	{
		return $this->belongsToMany(Animale::class, 'remision_recibe_animales')
			->withTimestamps();
	}

	public function persona()
	{
		return $this->belongsTo(Persona::class, 'cliente_id');
	}

	public function responsable()
	{
		return $this->belongsTo(User::class, 'responsable_id');
	}
	// Scope para filtrar por resultado
	public function scopeResultado($query, $valor)
	{
		if ($valor === 'con') {
			// Con resultado registrado (1)
			return $query->where('registro_resultado', 1);
		} elseif ($valor === 'sin') {
			// Sin resultado (0 o null)
			return $query->where(function ($q) {
				$q->where('registro_resultado', 0)
					->orWhereNull('registro_resultado');
			});
		}
		return $query; // 'todos'
	}

	// Scope para filtrar por estado
	public function scopeEstado($query, $valor)
	{
		if ($valor === 'aceptadas') {
			// Aceptadas = no rechazadas (0)
			return $query->where('rechazada', 0);
		} elseif ($valor === 'rechazadas') {
			// Rechazadas = 1
			return $query->where('rechazada', 1);
		}
		return $query; // 'todos'
	}

	public function todasTecnicasConResultado()
	{
		foreach ($this->tecnicas as $tecnica) {
			$resultado = DB::table('resultados')
				->join('muestra_recibe_tecnica', 'resultados.muestra_recibe_tecnica_id', '=', 'muestra_recibe_tecnica.id')
				->where('muestra_recibe_tecnica.tecnica_id', $tecnica->id)
				->where('muestra_recibe_tecnica.muestra_recibe_id', $this->id)
				->exists();

			if (!$resultado) {
				return false;
			}
		}
		return true;
	}
}
