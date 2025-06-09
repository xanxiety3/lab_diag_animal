<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TiposDocumento
 * 
 * @property int $id
 * @property string $nombre
 * 
 * @property Collection|Persona[] $personas
 *
 * @package App\Models
 */
class TiposDocumento extends Model
{
	protected $table = 'tipos_documento';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];

	public function personas()
	{
		return $this->hasMany(Persona::class, 'tipo_documento_id');
	}
}
