<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TauxOnline
 * 
 * @property int $id
 * @property string $taux
 * @property string $maj
 *
 * @package App\Models
 */
class TauxOnline extends Model
{
	protected $table = 'taux_online';
	public $timestamps = false;

	protected $fillable = [
		'taux',
		'maj'
	];
}
