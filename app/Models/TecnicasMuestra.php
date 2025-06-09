<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TecnicasMuestra
 * 
 * @property int $id
 * @property int $tipo_muestra_id
 * @property string $nombre
 * @property float|null $valor_unitario
 * 
 * @property TiposMuestra $tipos_muestra
 * @property Collection|RelacionItemsEnsayoDetallesMuestra[] $relacion_items_ensayo_detalles_muestras
 * @property Collection|RemisionMuestraRecibe[] $remision_muestra_recibes
 *
 * @package App\Models
 */
class TecnicasMuestra extends Model
{
	protected $table = 'tecnicas_muestra';
	public $timestamps = false;

	protected $casts = [
		'tipo_muestra_id' => 'int',
		'valor_unitario' => 'float'
	];

	protected $fillable = [
		'tipo_muestra_id',
		'nombre',
		'valor_unitario'
	];

	public function tipos_muestra()
	{
		return $this->belongsTo(TiposMuestra::class, 'tipo_muestra_id');
	}

	public function relacion_items_ensayo_detalles_muestras()
	{
		return $this->hasMany(RelacionItemsEnsayoDetallesMuestra::class);
	}

	
    public function muestrasRecibidas()
    {
        return $this->belongsToMany(MuestraRecibeTecnica::class, 'muestra_recibe_tecnica');
    }


}
