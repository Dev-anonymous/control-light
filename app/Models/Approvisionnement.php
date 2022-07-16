<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Approvisionnement
 *
 * @property int $id
 * @property int $article_id
 * @property int|null $qte
 * @property Carbon|null $date
 * @property int $compte_id
 *
 * @property Article $article
 *
 * @package App\Models
 */
class Approvisionnement extends Model
{
    protected $table = 'approvisionnement';
    public $timestamps = false;

    protected $casts = [
        'article_id' => 'int',
        'qte' => 'int',
        'compte_id' => 'int'
    ];

    protected $dates = [
        'date'
    ];

    protected $fillable = [
        'article_id',
        'qte',
        'date',
        'compte_id'
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
