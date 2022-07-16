<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class GroupeArticle
 *
 * @property int $id
 * @property string|null $groupe
 * @property int|null $par_defaut
 * @property int $compte_id
 *
 * @property Collection|CategorieArticle[] $categorie_articles
 *
 * @package App\Models
 */
class GroupeArticle extends Model
{
    protected $table = 'groupe_article';
    public $timestamps = false;

    protected $casts = [
        'par_defaut' => 'int',
        'compte_id' => 'int'
    ];

    protected $fillable = [
        'groupe',
        'par_defaut',
        'compte_id'
    ];

    public function categorie_articles()
    {
        return $this->hasMany(CategorieArticle::class);
    }
}
