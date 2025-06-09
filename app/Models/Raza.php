<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Raza
 * 
 * @property int $id
 * @property int|null $especie_id
 * @property string|null $nombre
 * 
 * @property Especy|null $especy
 * @property Collection|Animale[] $animales
 *
 * @package App\Models
 */
class Raza extends Model
{
	protected $table = 'razas';
	public $timestamps = false;

	protected $casts = [
		'especie_id' => 'int'
	];

	protected $fillable = [
		'especie_id',
		'nombre'
	];

	public function especy()
	{
		return $this->belongsTo(Especy::class, 'especie_id');
	}

	public function animales()
	{
		return $this->hasMany(Animale::class);
	}
}
