<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FactureSupprimee
 *
 * @property int $id
 * @property string|null $numero_facture
 * @property string|null $client
 * @property string|null $caissier
 * @property string|null $total
 * @property Carbon|null $date_facture
 * @property Carbon|null $date_suppression
 * @property string|null $articles
 * @property int|null $notifier
 * @property int $compte_id
 *
 * @package App\Models
 */
class FactureSupprimee extends Model
{
    protected $table = 'facture_supprimee';
    public $timestamps = false;

    protected $casts = [
        'notifier' => 'int',
        'compte_id' => 'int'
    ];

    protected $dates = [
        'date_facture',
        'date_suppression'
    ];

    protected $fillable = [
        'numero_facture',
        'client',
        'caissier',
        'total',
        'date_facture',
        'date_suppression',
        'articles',
        'notifier',
        'compte_id'
    ];
}
