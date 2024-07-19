<?php

use App\Models\Article;
use App\Models\Bonentree;
use App\Models\Compte;
use App\Models\Config;
use App\Models\Devise;
use App\Models\Facture;
use App\Models\FactureSupprimee;
use App\Models\Proforma;
use App\Models\Shop;
use App\Models\Taux;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;


function demo()
{
    $user = auth()->user();
    if ($user) {
        $emails = User::where('compte_id', $user->compte_id)->whereNotNull('email')->pluck('email')->all();
        foreach ($emails as $email) {
            if (strpos($email, 'demo.com') !== false) {
                abort(403, "ACTION NON AUTORISEE! CECI EST UN COMPTE TEST");
            }
        }
    }
}

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

function numero_facture()
{
    $num = Facture::where('compte_id', compte_id())->count() + 1;
    if ($num <= 9) {
        $num = "000$num";
    } else if ($num >= 10 and $num <= 99) {
        $num = "00$num";
    } else if ($num >= 100 and $num <= 999) {
        $num = "0$num";
    }

    $f = Facture::where(['compte_id' => compte_id(), 'numero_facture' => $num])->count();
    if ($f) {
        while (1) {
            $newnum = "$num-" . _code(4);
            $f = Facture::where(['compte_id' => compte_id(), 'numero_facture' => $newnum])->count();
            if (!$f) {
                $num = $newnum;
                break;
            }
        }
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

function encode($url)
{
    if (!is_file($url)) return;
    return 'data:' . mime_content_type($url) . ';base64,' . base64_encode(file_get_contents($url));
}

function proforma_dir()
{
    return glob(base_path() . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "templates" . DIRECTORY_SEPARATOR . "*", GLOB_ONLYDIR);
}

function shop()
{
    return Shop::where('compte_id', compte_id())->first();
}

function numero_proforma()
{
    $num = Proforma::where('compte_id', compte_id())->count() + 1;
    if ($num <= 9) {
        $num = "000$num";
    } else if ($num >= 10 and $num <= 99) {
        $num = "00$num";
    } else if ($num >= 100 and $num <= 999) {
        $num = "0$num";
    }

    $num = "FPRO-$num";

    $f = Proforma::where(['compte_id' => compte_id(), 'numero' => $num])->count();
    if ($f) {
        while (1) {
            $newnum = "$num-" . _code(4);
            $f = Proforma::where(['compte_id' => compte_id(), 'numero' => $newnum])->count();
            if (!$f) {
                $num = $newnum;
                break;
            }
        }
    }
    return $num;
}

function build_proforma($proforma)
{
    $rules =  [
        'nom_entreprise' => 'sometimes',
        'adresse_entreprise' => 'sometimes',
        'email_entreprise' => 'sometimes',
        'telephone_entreprise' => 'sometimes',
        'nom_client' => 'sometimes',
        'adresse_client' => 'sometimes',
        'email_client' => 'sometimes',
        'telephone_client' => 'sometimes',
        'mode_reglement' => 'sometimes',
        'condition_reglement' => 'sometimes',
        'date_reglement' => 'sometimes',
        'note_reglement' => 'sometimes',
        'articles' => 'sometimes|array',
        'articles.*' => 'required|exists:article,id',
        'qtes' => 'sometimes|array',
        'qtes.*' => 'min:1',
        'devise' => 'sometimes|in:CDF,USD'
    ];
    $validator = Validator::make(request()->all(), $rules);

    if ($validator->fails()) {
        return;
    }
    $data = $validator->validated();

    $devise = request()->devise ?? 'CDF';
    unset($data['devise']);

    $ids = (array) @$data['articles'];
    $qtes = (array) @$data['qtes'];

    $shop = Shop::where('compte_id', compte_id())->first();

    $t = [];
    foreach ($rules as $k => $e) {
        if ($k == 'qtes' or $k == 'articles' or $k == 'articles.*' or $k == 'qtes.*' or $k == 'devise') continue;
        if ($k == 'telephone_entreprise') {
            if (request($k)) {
                $t[] = request()->$k . "<br>RCCM : " . ($shop->rccm ?? '-') . "<br>IDNAT : " . ($shop->idnat ?? '-');
            } else {
                $t[] = '-';
            }
        } else {
            $t[] = request()->$k ?? '-';
        }
    }

    $art = Article::whereIn('id', $ids)->get();

    $tr = '';
    $tg = 0;
    $articles = [];
    foreach ($art as $k => $v) {
        $qte  = $qtes[$k];
        $tot = $v->prix * $qte;
        $tr .= "<tr>
            <td>" . ($k + 1) . "</td>
            <td>$v->article</td>
            <td>" . montant($v->prix) . " {$v->devise->devise}</td>
            <td>$qte</td>
            <td>{$v->unite_mesure->unite_mesure}</td>
            <td>$tot {$v->devise->devise}</td>
        </tr>";
        $tg += change($tot, $v->devise->devise, 'CDF');

        $art = (object) $v->toArray();
        $art->qte = $qte;
        $articles[] = $art;
    }
    $tg = change($tg, 'CDF', $devise);

    $keys = [
        '__ENTREPRISE_NOM__',
        '__ENTREPRISE_ADRESSE__',
        '__ENTREPRISE_EMAIL__',
        '__ENTREPRISE_TELEPHONE__',
        '__CLIENT_NOM__',
        '__CLIENT_ADRESSE__',
        '__CLIENT_EMAIL__',
        '__CLIENT_TELEPHONE__',
        '__MODE_REGLEMENT__',
        '__CONDITION_REGLEMENT__',
        '__DATE_REGLEMENT__',
        '__NOTE_REGLEMENT__',
        '__NUMERO_PROFORMA__',
        '__ARTICLE_PROFORMA__',
        '__TOTAL__',
        '__DATEPRO__'
    ];

    $num_fac = numero_proforma();
    $t[] = $num_fac;
    $t[] = $tr;
    $t[] = montant($tg, $devise);
    $t[] = now('Africa/Lubumbashi')->format('d-m-Y H:i');

    $temp = str_replace($keys, $t, $proforma);
    return (object) [
        'total' => $tg,
        'devise' => $devise,
        'numero' => $num_fac,
        'client' => [
            'nom' => request()->nom_client,
            'adresse' => request()->adresse_client,
            'tel' => request()->telephone_client,
            'email' => request()->email_client,
        ],
        'articles' => $articles,
        'proforma' => $temp
    ];
}

function getConfig($name = '', $compte_id =  null)
{
    if (!$compte_id) {
        $compte_id = compte_id();
    }

    $configOb = Config::where('compte_id', $compte_id)->first();
    if ($configOb) {
        $config = json_decode($configOb->config);
    } else {
        $config = (object) [];
    }
    if (empty($name)) {
        return $config;
    }
    return @$config->$name;
}

function setConfig($name, $value)
{
    $configOb = Config::where('compte_id', compte_id())->first();
    if ($configOb) {
        $config = json_decode($configOb->config);
    } else {
        $config = (object) [];
    }
    $config->$name = $value;
    $config = json_encode($config);

    if ($configOb) {
        $configOb->update(compact('config'));
    } else {
        Config::create(['compte_id' => compte_id(), 'config' => $config]);
    }
}

function marge(Article $article)
{
    $devto = $article->devise_achat;
    $pa = change($article->prix_achat, $article->devise_achat, $devto);
    $pv = change($article->prix, $article->devise->devise, $devto);

    $margeval = $pv - $pa;
    $mont = montant($margeval, $devto);
    $margtot = montant($margeval * $article->stock, $devto);

    if ($pa == $pv) {
        $margelabel = "Aucun bénéfice ne sera généré à la vente";
        $result = 'solde';
    } else if ($pa < $pv) {
        $margelabel = "Un bénéfice de $mont sera généré sur chaque vente par {$article->unite_mesure->unite_mesure}. Le bénéfice total sera de $margtot si tout le sock actuel est vendu.";
        $result = 'gain';
    } else {
        $margelabel = "Une perte de $mont sera générée sur chaque vente par {$article->unite_mesure->unite_mesure}. La perte totale sera de $margtot si tout le sock actuel est vendu.";
        $result = 'perte';
    }

    return (object) ['marge' => $mont, 'margelabel' => $margelabel, 'result' => $result];
}

function numbon($type = 'entre')
{
    if ($type == 'entre') {
        $n = Bonentree::count() + 1;
        $pr = 'BE';
    } elseif ($type == 'sortie') {
        $n = Bonentree::count() + 1;
        $pr = 'BS';
    } else {
        throw new Exception("uhm numbon()");
    }

    if ($n < 9) {
        $c = "$pr-00$n";
    } elseif ($n > 9 && $n < 100) {
        $c = "$pr-0$n";
    } else {
        $c = "$pr-$n";
    }

    return $c;
}


function templatepath()
{
    $role = auth()->user()->user_role;
    if (in_array($role, ['caissier', 'admin', 'client'])) {
        $cid = compte_id();
        $sep = DIRECTORY_SEPARATOR;
        $path = base_path() . "{$sep}ressources{$sep}" . "views{$sep}templates{$sep}$cid";
        if (!file_exists($path)) {
            // mkdir($path, 0777, true);
        }
        // dd($path, File::makeDirectory($path, 0777, true, true));
        return $path;
    }
}
