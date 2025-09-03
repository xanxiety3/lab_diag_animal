<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RemisionMuestraEnvio
 * 
 * @property int $id
 * @property Carbon|null $fecha
 * @property int $cliente_id

 * @property Persona $persona
 * @property Collection|DatosItemsMuestra[] $datos_items_muestras
 * @property Collection|RelacionItemsEnsayo[] $relacion_items_ensayos
 * @property Collection|RemisionMuestraRecibe[] $remision_muestra_recibes
 * @property Collection|VerificacionCriterio[] $verificacion_criterios
 *
 * @package App\Models
 */
class RemisionMuestraEnvio extends Model
{
	protected $table = 'remision_muestra_envio';
	public $timestamps = false;
	protected $casts = [
		
		'fecha' => 'datetime',

	];


    protected $fillable = ['fecha', 'cliente_id', 'observaciones'];

    public function tiposMuestras()
{
    return $this->belongsToMany(TiposMuestra::class, 'remision_tipo_muestra', 'remision_id', 'tipo_muestra_id')
        ->withPivot('cantidad_muestra', 'refrigeracion', 'observaciones')
        ->withTimestamps();
}



	public function persona()
	{
		return $this->belongsTo(Persona::class, 'cliente_id');
	}

	public function datos_items_muestras()
	{
		return $this->hasMany(DatosItemsMuestra::class, 'remision_id');
	}

	public function relacion_items_ensayos()
	{
		return $this->hasMany(RelacionItemsEnsayo::class, 'remision_id');
	}

	public function remision_muestra_recibe()
	{
		return $this->hasMany(RemisionMuestraRecibe::class, 'muestra_enviada_id');
	}

	public function verificacion_criterios()
	{
		return $this->hasMany(VerificacionCriterio::class, 'remision_id');
	}
}
