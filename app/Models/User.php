<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 *
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $user_role
 * @property string|null $phone
 * @property Carbon|null $derniere_activite
 * @property int|null $actif
 * @property int $compte_id
 *
 * @property Compte $compte
 * @property Collection|Facture[] $factures
 *
 * @package App\Models
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'users';

    protected $casts = [
        'actif' => 'int',
        'compte_id' => 'int'
    ];

    protected $dates = [
        'email_verified_at',
        'derniere_activite'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'user_role',
        'phone',
        'derniere_activite',
        'actif',
        'compte_id'
    ];

    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }

    public function factures()
    {
        return $this->hasMany(Facture::class, 'users_id');
    }
}
