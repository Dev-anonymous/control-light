<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CategorieArticle
 *
 * @property int $id
 * @property string|null $categorie
 * @property int $groupe_article_id
 * @property int|null $par_defaut
 * @property int $compte_id
 *
 * @property GroupeArticle $groupe_article
 * @property Collection|Article[] $articles
 *
 * @package App\Models
 */
class CategorieArticle extends Model
{
    protected $table = 'categorie_article';
    public $timestamps = false;

    protected $casts = [
        'groupe_article_id' => 'int',
        'par_defaut' => 'int',
        'compte_id' => 'int'
    ];

    protected $fillable = [
        'categorie',
        'groupe_article_id',
        'par_defaut',
        'compte_id'
    ];

    public function groupe_article()
    {
        return $this->belongsTo(GroupeArticle::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
