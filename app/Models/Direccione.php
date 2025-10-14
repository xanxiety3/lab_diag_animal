<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Direccione
 * 
 * @property int $id
 * @property int $cliente_id
 * @property int $municipio_id
 * @property int $tipo_direccion_id
 * @property int|null $tipo_ubicacion_id
 * @property string $direccion_detallada
 * 
 * @property Persona $persona
 * @property Municipio $municipio
 * @property TiposDireccion $tipos_direccion
 * @property TiposUbicacion|null $tipos_ubicacion
 *
 * @package App\Models
 */
class Direccione extends Model
{
	protected $table = 'direcciones';
	public $timestamps = false;

	protected $casts = [
		'cliente_id' => 'int',
		'municipio_id' => 'int',
		'tipo_direccion_id' => 'int',
		'tipo_ubicacion_id' => 'int'
	];

	protected $fillable = [
		'cliente_id',
		'municipio_id',
		'tipo_direccion_id',
		'tipo_ubicacion_id',
		'nombre_predio',
		'direccion_detallada'
	];

	public function persona()
	{
		return $this->belongsTo(Persona::class, 'cliente_id');
	}

	public function municipio()
	{
		return $this->belongsTo(Municipio::class);
	}

	public function tipos_direccion()
	{
		return $this->belongsTo(TiposDireccion::class, 'tipo_direccion_id');
	}

	public function tipos_ubicacion()
	{
		return $this->belongsTo(TiposUbicacion::class, 'tipo_ubicacion_id');
	}
}
