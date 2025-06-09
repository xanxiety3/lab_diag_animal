<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sexo
 * 
 * @property int $id
 * @property string|null $descripcion
 * 
 * @property Collection|Animale[] $animales
 *
 * @package App\Models
 */
class Sexo extends Model
{
	protected $table = 'sexos';
	public $timestamps = false;

	protected $fillable = [
		'descripcion'
	];

	public function animales()
	{
		return $this->hasMany(Animale::class);
	}
}
