<?php

use App\Models\Article;
use App\Models\Devise;
use App\Models\Facture;
use App\Models\FactureSupprimee;
use App\Models\Taux;
use PhpParser\Node\Expr\Cast\Double;

function montant($montant, $devise = '')
{
    return  number_format($montant, 2, '.', ' ') . (empty($devise) ? '' : " $devise");
}

function code_article()
{
    $n = 0;
    $ok = false;
    while ($n < 100000) {
        $c = _code() . '-' . _code() . '-' . _code() . '-' . _code();
        $n++;
        if (!Article::where('code', $c)->first()) {
            $ok = true;
            break;
        }
    }
    return $ok ? $c : '-';
}

function _code($lengh = 4)
{
    return (int) substr(rand(time(), time() * time()), 0, $lengh);
}

function numero_facture($num)
{
    if ($num <= 9) {
        $num = "000$num";
    } else if ($num >= 10 and $num <= 99) {
        $num = "00$num";
    } else if ($num >= 100 and $num <= 999) {
        $num = "0$num";
    }
    return $num;
}

function change($montant, $from, $to)
{
    if (!in_array(strtoupper($from), ['USD', 'CDF']) or !in_array(strtoupper($to), ['USD', 'CDF'])) {
        return  $montant;
    }

    $df = Devise::where(['devise' => $from])->first();
    $dt = Devise::where(['devise' => $to])->first();

    if (!$df or !$dt) {
        return  $montant;
    }

    $taux = Taux::where(['devise_id' => $df->id, 'devise2_id' => $dt->id, 'compte_id' => compte_id()])->get()->first();
    if ($taux) {
        $montant = $montant * $taux->taux;
    }
    return  round($montant, 2);
}

function magasinOk()
{
    $ta = Article::where('compte_id', compte_id())->get()->count();
    $nf = Facture::where('compte_id', compte_id())->get()->count();

    $min = date('Y-m-d');
    $max = date('Y-m-d', strtotime('+61 days'));
    $expMois = Article::where(function ($query) use ($min, $max) {
        $query->whereNotNull('date_expiration');
        $query->whereDate('date_expiration', '>=', $min);
        $query->whereDate('date_expiration', '<=', $max);
        $query->where('compte_id', compte_id());
    })->count();

    $exp = Article::where(function ($query) use ($min, $max) {
        $query->whereNotNull('date_expiration')->whereDate('date_expiration', '<', date('Y-m-d'));
        $query->where('compte_id', compte_id());
    })->count();

    $stock = Article::where('stock', '<', 20)->where('compte_id', compte_id())
        ->orderBy('stock', 'desc')
        ->get()
        ->count();

    $ok = true;
    if ($expMois or $exp or $stock) {
        $ok = false;
    }
    return (object) [
        'ok' => $ok,
        'ta' => $ta,
        'nf' => $nf,
        'expMois' => $expMois,
        'exp' => $exp,
        'stock' => $stock,
    ];
}

function facture_supprimee()
{
    return FactureSupprimee::where('notifier', 1)->where('compte_id', compte_id())->get()->count();
}

function compte_id()
{
    return (int)  auth()->user()->compte_id;
}

function reduction($montant, $pourc)
{
    return round($montant - ($montant * $pourc / 100), 2);
}
