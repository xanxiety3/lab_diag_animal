<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TiposMuestra
 * 
 * @property int $id
 * @property string $nombre
 * 
 * @property Collection|RelacionItemsEnsayoDetallesMuestra[] $relacion_items_ensayo_detalles_muestras
 * @property Collection|RemisionMuestraEnvio[] $remision_muestra_envios
 * @property Collection|TecnicasMuestra[] $tecnicas_muestras
 *
 * @package App\Models
 */
class TiposMuestra extends Model
{
	protected $table = 'tipos_muestra';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];

	public function relacion_items_ensayo_detalles_muestras()
	{
		return $this->hasMany(RelacionItemsEnsayoDetallesMuestra::class, 'tipo_muestra_id');
	}

	   public function remisiones()
    {
        return $this->belongsToMany(RemisionMuestraEnvio::class, 'remision_tipo_muestra')
            ->withPivot('cantidad_muestra', 'refrigeracion', 'observaciones')
            ->withTimestamps();
    }

	public function tecnicas_muestras()
	{
		return $this->hasMany(TecnicasMuestra::class, 'tipo_muestra_id');
	}
}
