<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Compte
 *
 * @property int $id
 * @property string|null $client
 * @property string|null $email
 * @property string|null $magasin
 * @property Carbon|null $date_creation
 * @property int|null $actif
 * @property string|null $phone
 * @property string|null $type
 * @property string|null $token
 * @property string|null $message
 *
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Compte extends Model
{
    protected $table = 'compte';
    public $timestamps = false;

    protected $casts = [
        'actif' => 'int'
    ];

    protected $dates = [
        'date_creation'
    ];

    protected $hidden = [
        'token'
    ];

    protected $fillable = [
        'client',
        'email',
        'magasin',
        'date_creation',
        'actif',
        'phone',
        'type',
        'token',
        'message'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
