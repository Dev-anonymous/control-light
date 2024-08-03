<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Proforma
 * 
 * @property int $id
 * @property int|null $users_id
 * @property int|null $client_id
 * @property string|null $numero
 * @property string|null $client
 * @property string|null $html
 * @property string|null $article
 * @property Carbon|null $date
 * @property int $compte_id
 * @property string|null $montant
 * @property string|null $enregistrer_par
 * @property Carbon|null $date_encaissement
 * @property int|null $isprint
 * 
 * @property User|null $user
 * @property Collection|Bonsortie[] $bonsorties
 *
 * @package App\Models
 */
class Proforma extends Model
{
	protected $table = 'proforma';
	public $timestamps = false;

	protected $casts = [
		'users_id' => 'int',
		'client_id' => 'int',
		'compte_id' => 'int',
		'isprint' => 'int'
	];

	protected $dates = [
		'date',
		'date_encaissement'
	];

	protected $fillable = [
		'users_id',
		'client_id',
		'numero',
		'client',
		'html',
		'article',
		'date',
		'compte_id',
		'montant',
		'enregistrer_par',
		'date_encaissement',
		'isprint'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'users_id');
	}

	public function bonsorties()
	{
		return $this->hasMany(Bonsortie::class);
	}
}
