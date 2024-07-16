<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Vente
 * 
 * @property int $id
 * @property int $facture_id
 * @property int|null $article_id
 * @property string $article
 * @property int|null $groupe_article_id
 * @property string|null $groupe
 * @property int|null $categorie_article_id
 * @property string $categorie_article
 * @property int|null $qte
 * @property float|null $prix
 * @property float|null $reduction
 * @property string|null $unite_mesure
 * @property string|null $devise
 * @property int|null $vente_a_credit
 * @property int|null $etat_payement
 * @property string|null $code
 * @property int $compte_id
 * @property float|null $marge_cdf
 * @property float|null $marge_usd
 * 
 * @property Facture $facture
 * @property Collection|DetailCredit[] $detail_credits
 *
 * @package App\Models
 */
class Vente extends Model
{
	protected $table = 'vente';
	public $timestamps = false;

	protected $casts = [
		'facture_id' => 'int',
		'article_id' => 'int',
		'groupe_article_id' => 'int',
		'categorie_article_id' => 'int',
		'qte' => 'int',
		'prix' => 'float',
		'reduction' => 'float',
		'vente_a_credit' => 'int',
		'etat_payement' => 'int',
		'compte_id' => 'int',
		'marge_cdf' => 'float',
		'marge_usd' => 'float'
	];

	protected $fillable = [
		'facture_id',
		'article_id',
		'article',
		'groupe_article_id',
		'groupe',
		'categorie_article_id',
		'categorie_article',
		'qte',
		'prix',
		'reduction',
		'unite_mesure',
		'devise',
		'vente_a_credit',
		'etat_payement',
		'code',
		'compte_id',
		'marge_cdf',
		'marge_usd'
	];

	public function article()
	{
		return $this->belongsTo(Article::class);
	}

	public function facture()
	{
		return $this->belongsTo(Facture::class);
	}

	public function detail_credits()
	{
		return $this->hasMany(DetailCredit::class);
	}
}
