<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UniteMesure
 *
 * @property int $id
 * @property string|null $unite_mesure
 * @property int|null $par_defaut
 * @property int $compte_id
 *
 * @property Collection|Article[] $articles
 *
 * @package App\Models
 */
class UniteMesure extends Model
{
    protected $table = 'unite_mesure';
    public $timestamps = false;

    protected $casts = [
        'par_defaut' => 'int',
        'compte_id' => 'int'
    ];

    protected $fillable = [
        'unite_mesure',
        'par_defaut',
        'compte_id'
    ];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
