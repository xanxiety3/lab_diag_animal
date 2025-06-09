<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RelacionItemsEnsayo
 * 
 * @property int $id
 * @property int $id_cliente
 * @property bool $empresa
 * @property string|null $nombre_empresa
 * @property bool $cliente_sena
 * @property string|null $rol_sena
 * @property int $id_responsable
 * @property int $remision_id
 * 
 * @property RemisionMuestraEnvio $remision_muestra_envio
 * @property Collection|RelacionItemsEnsayoDetallesMuestra[] $relacion_items_ensayo_detalles_muestras
 *
 * @package App\Models
 */
class RelacionItemsEnsayo extends Model
{
	protected $table = 'relacion_items_ensayo';
	public $timestamps = false;

	protected $casts = [
		'id_cliente' => 'int',
		'empresa' => 'bool',
		'cliente_sena' => 'bool',
		'id_responsable' => 'int',
		'remision_id' => 'int'
	];

	protected $fillable = [
		'id_cliente',
		'empresa',
		'nombre_empresa',
		'cliente_sena',
		'rol_sena',
		'id_responsable',
		'remision_id'
	];

	public function remision_muestra_envio()
	{
		return $this->belongsTo(RemisionMuestraEnvio::class, 'remision_id');
	}

	public function relacion_items_ensayo_detalles_muestras()
	{
		return $this->hasMany(RelacionItemsEnsayoDetallesMuestra::class, 'id_items_ensayo');
	}
}
