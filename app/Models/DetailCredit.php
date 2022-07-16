<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DetailCredit
 * 
 * @property int $id
 * @property int $vente_id
 * @property string|null $client
 * @property Carbon|null $date_payement
 * @property Carbon|null $date_prevu_payement
 * 
 * @property Vente $vente
 *
 * @package App\Models
 */
class DetailCredit extends Model
{
	protected $table = 'detail_credit';
	public $timestamps = false;

	protected $casts = [
		'vente_id' => 'int'
	];

	protected $dates = [
		'date_payement',
		'date_prevu_payement'
	];

	protected $fillable = [
		'vente_id',
		'client',
		'date_payement',
		'date_prevu_payement'
	];

	public function vente()
	{
		return $this->belongsTo(Vente::class);
	}
}
