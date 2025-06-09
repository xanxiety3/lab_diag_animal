<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Municipio
 * 
 * @property int $id
 * @property int $departamento_id
 * @property string $nombre
 * 
 * @property Departamento $departamento
 * @property Collection|Direccione[] $direcciones
 *
 * @package App\Models
 */
class Municipio extends Model
{
	protected $table = 'municipios';
	public $timestamps = false;

	protected $casts = [
		'departamento_id' => 'int'
	];

	protected $fillable = [
		'departamento_id',
		'nombre'
	];

	public function departamento()
	{
		return $this->belongsTo(Departamento::class);
	}

	public function direcciones()
	{
		return $this->hasMany(Direccione::class);
	}
}
