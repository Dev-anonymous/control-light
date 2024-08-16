<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Code
 * 
 * @property int $id
 * @property int $article_id
 * @property string|null $code
 * @property Carbon|null $date
 * 
 * @property Article $article
 *
 * @package App\Models
 */
class Code extends Model
{
	protected $table = 'code';
	public $timestamps = false;

	protected $casts = [
		'article_id' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'article_id',
		'code',
		'date'
	];

	public function article()
	{
		return $this->belongsTo(Article::class);
	}
}
