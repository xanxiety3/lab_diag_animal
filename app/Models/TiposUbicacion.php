<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TiposUbicacion
 * 
 * @property int $id
 * @property string $nombre
 * 
 * @property Collection|Direccione[] $direcciones
 *
 * @package App\Models
 */
class TiposUbicacion extends Model
{
	protected $table = 'tipos_ubicacion';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];

	public function direcciones()
	{
		return $this->hasMany(Direccione::class, 'tipo_ubicacion_id');
	}
}
