<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Bonentree
 * 
 * @property int $id
 * @property float|null $total_cdf
 * @property string|null $numero
 * @property Carbon|null $date
 * @property int|null $status
 * @property int $compte_id
 * @property string|null $emetteur
 * @property string|null $rejete_par
 * @property string|null $valide_par
 * 
 * @property Collection|Article[] $articles
 *
 * @package App\Models
 */
class Bonentree extends Model
{
	protected $table = 'bonentree';
	public $timestamps = false;

	protected $casts = [
		'total_cdf' => 'float',
		'status' => 'int',
		'compte_id' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'total_cdf',
		'numero',
		'date',
		'status',
		'compte_id',
		'emetteur',
		'rejete_par',
		'valide_par'
	];

	public function articles()
	{
		return $this->belongsToMany(Article::class)
					->withPivot('id', 'article', 'prix_achat', 'devise_achat', 'prix_vente', 'devise_vente', 'qte', 'date_exiparation');
	}
}
