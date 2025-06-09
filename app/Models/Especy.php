<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Especy
 * 
 * @property int $id
 * @property string|null $nombre
 * 
 * @property Collection|Animale[] $animales
 * @property Collection|Raza[] $razas
 *
 * @package App\Models
 */
class Especy extends Model
{
	protected $table = 'especies';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];

	public function animales()
	{
		return $this->hasMany(Animale::class, 'especie_id');
	}

	public function razas()
	{
		return $this->hasMany(Raza::class, 'especie_id');
	}
}
