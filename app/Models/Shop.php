<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Shop
 * 
 * @property int $idshop
 * @property string|null $shop
 * @property string|null $adresse
 * @property string|null $contact
 * @property Carbon|null $date_creation
 * @property int $compte_id
 * @property string|null $rccm
 * @property string|null $idnat
 *
 * @package App\Models
 */
class Shop extends Model
{
	protected $table = 'shop';
	protected $primaryKey = 'idshop';
	public $timestamps = false;

	protected $casts = [
		'compte_id' => 'int'
	];

	protected $dates = [
		'date_creation'
	];

	protected $fillable = [
		'shop',
		'adresse',
		'contact',
		'date_creation',
		'compte_id',
		'rccm',
		'idnat'
	];
}
