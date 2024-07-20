<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ArticleBonsortie
 * 
 * @property int $id
 * @property int $bonsortie_id
 * @property int|null $article_id
 * @property string|null $article
 * @property float|null $prix_vente
 * @property string|null $devise_vente
 * @property int|null $qte
 * 
 * @property Bonsortie $bonsortie
 *
 * @package App\Models
 */
class ArticleBonsortie extends Model
{
	protected $table = 'article_bonsortie';
	public $timestamps = false;

	protected $casts = [
		'bonsortie_id' => 'int',
		'article_id' => 'int',
		'prix_vente' => 'float',
		'qte' => 'int'
	];

	protected $fillable = [
		'bonsortie_id',
		'article_id',
		'article',
		'prix_vente',
		'devise_vente',
		'qte'
	];

	public function article()
	{
		return $this->belongsTo(Article::class);
	}

	public function bonsortie()
	{
		return $this->belongsTo(Bonsortie::class);
	}
}
