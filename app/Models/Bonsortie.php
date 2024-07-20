<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Bonsortie
 * 
 * @property int $id
 * @property float|null $total_cdf
 * @property string|null $numero
 * @property int|null $status
 * @property int $compte_id
 * @property string|null $emetteur
 * @property string|null $rejeter_par
 * @property string|null $valider_par
 * @property Carbon|null $date
 * @property string|null $type
 * @property string|null $motif
 * 
 * @property Collection|Article[] $articles
 * @property Collection|BonLivraison[] $bon_livraisons
 *
 * @package App\Models
 */
class Bonsortie extends Model
{
	protected $table = 'bonsortie';
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
		'status',
		'compte_id',
		'emetteur',
		'rejeter_par',
		'valider_par',
		'date',
		'type',
		'motif'
	];

	public function articles()
	{
		return $this->belongsToMany(Article::class)
					->withPivot('id', 'article', 'prix_vente', 'devise_vente', 'qte');
	}

	public function bon_livraisons()
	{
		return $this->hasMany(BonLivraison::class);
	}
}
