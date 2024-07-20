<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BonLivraison
 * 
 * @property int $id
 * @property int $bonsortie_id
 * @property string|null $nomclient
 * @property string|null $telephoneclient
 * @property string|null $adresseclient
 * @property string|null $adresselivraison
 * @property string|null $chauffeur
 * @property string|null $numerovehicule
 * @property Carbon|null $datelivraison
 * 
 * @property Bonsortie $bonsortie
 *
 * @package App\Models
 */
class BonLivraison extends Model
{
	protected $table = 'bon_livraison';
	public $timestamps = false;

	protected $casts = [
		'bonsortie_id' => 'int'
	];

	protected $dates = [
		'datelivraison'
	];

	protected $fillable = [
		'bonsortie_id',
		'nomclient',
		'telephoneclient',
		'adresseclient',
		'adresselivraison',
		'chauffeur',
		'numerovehicule',
		'datelivraison'
	];

	public function bonsortie()
	{
		return $this->belongsTo(Bonsortie::class);
	}
}
