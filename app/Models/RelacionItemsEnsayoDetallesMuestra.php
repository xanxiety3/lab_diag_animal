<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RelacionItemsEnsayoDetallesMuestra
 * 
 * @property int $id
 * @property int $tipo_muestra_id
 * @property int $tecnicas_muestra_id
 * @property int $animal_id
 * @property string|null $observaciones
 * @property int $id_items_ensayo
 * 
 * @property RelacionItemsEnsayo $relacion_items_ensayo
 * @property TiposMuestra $tipos_muestra
 * @property Animale $animale
 * @property TecnicasMuestra $tecnicas_muestra
 *
 * @package App\Models
 */
class RelacionItemsEnsayoDetallesMuestra extends Model
{
	protected $table = 'relacion_items_ensayo_detalles_muestra';
	public $timestamps = false;

	protected $casts = [
		'tipo_muestra_id' => 'int',
		'tecnicas_muestra_id' => 'int',
		'animal_id' => 'int',
		'id_items_ensayo' => 'int'
	];

	protected $fillable = [
		'tipo_muestra_id',
		'tecnicas_muestra_id',
		'animal_id',
		'observaciones',
		'id_items_ensayo'
	];

	public function relacion_items_ensayo()
	{
		return $this->belongsTo(RelacionItemsEnsayo::class, 'id_items_ensayo');
	}

	public function tipos_muestra()
	{
		return $this->belongsTo(TiposMuestra::class, 'tipo_muestra_id');
	}

	public function animale()
	{
		return $this->belongsTo(Animale::class, 'animal_id');
	}

	public function tecnicas_muestra()
	{
		return $this->belongsTo(TecnicasMuestra::class);
	}
}
