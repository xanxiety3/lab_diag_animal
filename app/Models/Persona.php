<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Persona
 * 
 * @property int $id
 * @property int $tipo_documento_id
 * @property string $numero_documento
 * @property string $nombres
 * @property string $apellidos
 * @property string|null $correo
 * @property string|null $telefono
 * 
 * @property TiposDocumento $tipos_documento
 * @property Collection|Animale[] $animales
 * @property Collection|Direccione[] $direcciones
 * @property Collection|RemisionMuestraEnvio[] $remision_muestra_envios
 * @property Collection|RemisionMuestraRecibe[] $remision_muestra_recibes
 *
 * @package App\Models
 */
class Persona extends Model
{
	protected $table = 'personas';
	public $timestamps = false;

	protected $casts = [
		'tipo_documento_id' => 'int'
	];

	protected $fillable = [
		'tipo_documento_id',
		'numero_documento',
		'nombres',
		'apellidos',
		'correo',
		'telefono'
	];

	public function tipos_documento()
	{
		return $this->belongsTo(TiposDocumento::class, 'tipo_documento_id');
	}

	public function animales()
	{
		return $this->hasMany(Animale::class, 'duenio_id');
	}

	public function direcciones()
	{
		return $this->hasMany(Direccione::class, 'cliente_id');
	}

	public function remision_muestra_envios()
	{
		return $this->hasMany(RemisionMuestraEnvio::class, 'cliente_id');
	}

	public function remision_muestra_recibes()
	{
		return $this->hasMany(RemisionMuestraRecibe::class, 'responsable_id');
	}
}
