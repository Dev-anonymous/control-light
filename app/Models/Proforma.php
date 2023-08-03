<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Proforma
 * 
 * @property int $id
 * @property string|null $numero
 * @property string|null $client
 * @property string|null $html
 * @property string|null $article
 * @property Carbon|null $date
 * @property int $compte_id
 * @property string|null $montant
 * @property string|null $enregistrer_par
 * @property Carbon|null $date_encaissement
 *
 * @package App\Models
 */
class Proforma extends Model
{
	protected $table = 'proforma';
	public $timestamps = false;

	protected $casts = [
		'compte_id' => 'int'
	];

	protected $dates = [
		'date',
		'date_encaissement'
	];

	protected $fillable = [
		'numero',
		'client',
		'html',
		'article',
		'date',
		'compte_id',
		'montant',
		'enregistrer_par',
		'date_encaissement'
	];
}
