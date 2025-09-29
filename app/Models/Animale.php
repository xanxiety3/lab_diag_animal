<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Animale
 * 
 * @property int $id
 * @property int $duenio_id
 * @property int|null $especie_id
 * @property int|null $raza_id
 * @property int|null $sexo_id
 * @property int|null $edad
 * 
 * @property Persona $persona
 * @property Especie|null $especie
 * @property Raza|null $raza
 * @property Sexo|null $sexo
 * @property Collection|RelacionItemsEnsayoDetallesMuestra[] $relacion_items_ensayo_detalles_muestras
 *
 * @package App\Models
 */
class Animale extends Model
{
	protected $table = 'animales';
	public $timestamps = false;

	protected $casts = [
		'duenio_id' => 'int',
		'especie_id' => 'int',
		'raza_id' => 'int',
		'sexo_id' => 'int',
		'edad' => 'int'
	];

	protected $fillable = [
		'duenio_id',
		'nombre',
		'especie_id',
		'raza_id',
		'sexo_id',
		'edad'
	];

	public function persona()
	{
		return $this->belongsTo(Persona::class, 'duenio_id');
	}

	public function especie()
	{
		return $this->belongsTo(Especy::class, 'especie_id');
	}

	public function raza()
	{
		return $this->belongsTo(Raza::class);
	}

	public function sexo()
	{
		return $this->belongsTo(Sexo::class);
	}

	public function relacion_items_ensayo_detalles_muestras()
	{
		return $this->hasMany(RelacionItemsEnsayoDetallesMuestra::class, 'animal_id');
	}

	public function remisionesRecibidas()
	{
		return $this->belongsToMany(RemisionMuestraRecibe::class, 'remision_recibe_animales')
			->withTimestamps();
	}

	public function tecnicasAsignadas()
	{
		return $this->belongsToMany(
			TecnicasMuestra::class,
			'animal_tecnica_resultado', // tabla pivote
			'animal_id',                // clave en la pivote que referencia a animales
			'tecnica_id'                // clave en la pivote que referencia a tecnicas
		)->withPivot('id', 'remision_muestra_recibe_id'); // 👈 ahora sí puedes acceder al pivot
	}
}
