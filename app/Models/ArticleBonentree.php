<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ArticleBonentree
 * 
 * @property int $id
 * @property int $bonentree_id
 * @property int|null $article_id
 * @property string|null $article
 * @property float|null $prix_achat
 * @property string|null $devise_achat
 * @property float|null $prix_vente
 * @property string|null $devise_vente
 * @property int|null $qte
 * @property Carbon|null $date_exiparation
 * 
 * @property Bonentree $bonentree
 *
 * @package App\Models
 */
class ArticleBonentree extends Model
{
	protected $table = 'article_bonentree';
	public $timestamps = false;

	protected $casts = [
		'bonentree_id' => 'int',
		'article_id' => 'int',
		'prix_achat' => 'float',
		'prix_vente' => 'float',
		'qte' => 'int'
	];

	protected $dates = [
		'date_exiparation'
	];

	protected $fillable = [
		'bonentree_id',
		'article_id',
		'article',
		'prix_achat',
		'devise_achat',
		'prix_vente',
		'devise_vente',
		'qte',
		'date_exiparation'
	];

	public function article()
	{
		return $this->belongsTo(Article::class);
	}

	public function bonentree()
	{
		return $this->belongsTo(Bonentree::class);
	}
}
