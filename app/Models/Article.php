<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Article
 * 
 * @property int $id
 * @property string $article
 * @property int $categorie_article_id
 * @property int $unite_mesure_id
 * @property float|null $reduction
 * @property float $prix
 * @property int $devise_id
 * @property Carbon|null $date_expiration
 * @property string $code
 * @property int $stock
 * @property int $compte_id
 * @property float|null $prix_achat
 * @property string|null $devise_achat
 * 
 * @property CategorieArticle $categorie_article
 * @property Devise $devise
 * @property UniteMesure $unite_mesure
 * @property Collection|Approvisionnement[] $approvisionnements
 * @property Collection|Bonentree[] $bonentrees
 * @property Collection|Vente[] $ventes
 *
 * @package App\Models
 */
class Article extends Model
{
	protected $table = 'article';
	public $timestamps = false;

	protected $casts = [
		'categorie_article_id' => 'int',
		'unite_mesure_id' => 'int',
		'reduction' => 'float',
		'prix' => 'float',
		'devise_id' => 'int',
		'stock' => 'int',
		'compte_id' => 'int',
		'prix_achat' => 'float'
	];

	protected $dates = [
		'date_expiration'
	];

	protected $fillable = [
		'article',
		'categorie_article_id',
		'unite_mesure_id',
		'reduction',
		'prix',
		'devise_id',
		'date_expiration',
		'code',
		'stock',
		'compte_id',
		'prix_achat',
		'devise_achat'
	];

	public function categorie_article()
	{
		return $this->belongsTo(CategorieArticle::class);
	}

	public function devise()
	{
		return $this->belongsTo(Devise::class);
	}

	public function unite_mesure()
	{
		return $this->belongsTo(UniteMesure::class);
	}

	public function approvisionnements()
	{
		return $this->hasMany(Approvisionnement::class);
	}

	public function bonentrees()
	{
		return $this->belongsToMany(Bonentree::class)
					->withPivot('id', 'article', 'prix_achat', 'devise_achat', 'prix_vente', 'devise_vente', 'qte', 'date_exiparation');
	}

	public function ventes()
	{
		return $this->hasMany(Vente::class);
	}
}
