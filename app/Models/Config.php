<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Config
 * 
 * @property int $id
 * @property int $compte_id
 * @property string|null $config
 * 
 * @property Compte $compte
 *
 * @package App\Models
 */
class Config extends Model
{
	protected $table = 'config';
	public $timestamps = false;

	protected $casts = [
		'compte_id' => 'int'
	];

	protected $fillable = [
		'compte_id',
		'config'
	];

	public function compte()
	{
		return $this->belongsTo(Compte::class);
	}
}
