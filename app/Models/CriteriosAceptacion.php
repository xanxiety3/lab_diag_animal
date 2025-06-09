<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CriteriosAceptacion
 * 
 * @property int $id
 * @property string $descripcion
 * 
 * @property Collection|VerificacionCriterio[] $verificacion_criterios
 *
 * @package App\Models
 */
class CriteriosAceptacion extends Model
{
	protected $table = 'criterios_aceptacion';
	public $timestamps = false;

	protected $fillable = [
		'descripcion'
	];

	public function verificacion_criterios()
	{
		return $this->hasMany(VerificacionCriterio::class, 'criterio_id');
	}
}
