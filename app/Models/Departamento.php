<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Departamento
 * 
 * @property int $id
 * @property string $nombre
 * 
 * @property Collection|Municipio[] $municipios
 *
 * @package App\Models
 */
class Departamento extends Model
{
	protected $table = 'departamentos';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];

	public function municipios()
	{
		return $this->hasMany(Municipio::class);
	}
}
