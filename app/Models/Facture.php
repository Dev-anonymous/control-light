<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Facture
 *
 * @property int $id
 * @property int|null $users_id
 * @property string|null $client
 * @property string|null $caissier
 * @property float|null $total
 * @property string|null $devise
 * @property Carbon|null $date
 * @property int $compte_id
 *
 * @property User|null $user
 * @property Collection|Vente[] $ventes
 *
 * @package App\Models
 */
class Facture extends Model
{
    protected $table = 'facture';
    public $timestamps = false;

    protected $casts = [
        'users_id' => 'int',
        'total' => 'float',
        'compte_id' => 'int'
    ];

    protected $dates = [
        'date'
    ];

    protected $fillable = [
        'users_id',
        'client',
        'caissier',
        'total',
        'devise',
        'date',
        'compte_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function ventes()
    {
        return $this->hasMany(Vente::class);
    }
}
