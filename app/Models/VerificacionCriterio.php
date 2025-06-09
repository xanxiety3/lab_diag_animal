<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VerificacionCriterio
 * 
 * @property int $id
 * @property int $criterio_id
 * @property bool $cumple
 * @property string|null $observaciones
 * @property int $remision_id
 * 
 * @property RemisionMuestraEnvio $remision_muestra_envio
 * @property CriteriosAceptacion $criterios_aceptacion
 *
 * @package App\Models
 */
class VerificacionCriterio extends Model
{
	protected $table = 'verificacion_criterios';
	public $timestamps = false;

	protected $casts = [
		'criterio_id' => 'int',
		'cumple' => 'bool',
		'remision_id' => 'int'
	];

	protected $fillable = [
		'criterio_id',
		'cumple',
		'observaciones',
		'remision_id'
	];

	public function remision_muestra_envio()
	{
		return $this->belongsTo(RemisionMuestraEnvio::class, 'remision_id');
	}

	public function criterios_aceptacion()
	{
		return $this->belongsTo(CriteriosAceptacion::class, 'criterio_id');
	}
}
