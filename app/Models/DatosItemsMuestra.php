<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DatosItemsMuestra
 * 
 * @property int $id
 * @property int $remision_id
 * @property string|null $tipo_empaque
 * @property bool|null $cantidad_requerida
 * @property float|null $temperatura_recepcion
 * @property string|null $observaciones
 * @property bool|null $items_aceptado
 * @property string|null $codigo_interno
 * 
 * @property RemisionMuestraEnvio $remision_muestra_envio
 *
 * @package App\Models
 */
class DatosItemsMuestra extends Model
{
	protected $table = 'datos_items_muestra';
	public $timestamps = false;

	protected $casts = [
		'remision_id' => 'int',
		'cantidad_requerida' => 'bool',
		'temperatura_recepcion' => 'float',
		'items_aceptado' => 'bool'
	];

	protected $fillable = [
		'remision_id',
		'tipo_empaque',
		'cantidad_requerida',
		'temperatura_recepcion',
		'observaciones',
		'items_aceptado',
		'codigo_interno'
	];

	public function remision_muestra_envio()
	{
		return $this->belongsTo(RemisionMuestraEnvio::class, 'remision_id');
	}
}
