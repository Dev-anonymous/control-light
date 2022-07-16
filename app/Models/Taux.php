<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Taux
 *
 * @property int $id
 * @property int $devise_id
 * @property int $devise2_id
 * @property float|null $taux
 * @property int $compte_id
 * 
 * @property Devise $devise
 *
 * @package App\Models
 */
class Taux extends Model
{
    protected $table = 'taux';
    public $timestamps = false;

    protected $casts = [
        'devise_id' => 'int',
        'devise2_id' => 'int',
        'taux' => 'float',
        'compte_id' => 'int'
    ];

    protected $fillable = [
        'devise_id',
        'devise2_id',
        'taux',
        'compte_id'
    ];

    public function devise()
    {
        return $this->belongsTo(Devise::class);
    }
}
