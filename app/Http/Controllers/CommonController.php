<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Proforma;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            abort_if(!in_array(auth()->user()->user_role, ['admin', 'caissier', 'gerant']), 503);
            return $next($request);
        });
    }
    public function modele_proforma()
    {
        $p =  proforma_dir();
        $models = [];
        foreach ($p as $k => $pa) {
            $id = $k + 1;
            $pro = file_get_contents("$pa/proforma");
            $img = encode("$pa/image.png");
            $models[] = (object) ['id' => $id, 'img' => $img];
        }
        return view('common.modele-proforma', compact('models'));
    }
    public function facture_proforma($id)
    {
        $p =  proforma_dir();
        if ($id > count($p)) {
            abort(404);
        }
        $proforma_id = $id;
        $path = $p[$id - 1];
        $pro = file_get_contents("$path/proforma");
        $modele = (object) ['id' => $id, 'data' => $pro];

        $req = Request::create(route('articles.index', ['filtre' => true]));
        $resp = app()->handle($req);
        $resp = json_decode($resp->getContent());
        $articles = $resp->data;
        $shop = shop();
        $email = User::where(['user_role' => 'admin', 'compte_id' => compte_id()])->first()->email;

        $clients = User::where('user_role', 'client')->where('compte_id', compte_id())->get();

        return view('common.facture-proforma', compact('modele', 'articles', 'shop', 'email', 'proforma_id', 'clients'));
    }

    public function preview_proforma($id)
    {
        $p =  proforma_dir();
        if ($id > count($p)) {
            abort(404);
        }
        $path = $p[$id - 1];
        $pro = file_get_contents("$path/proforma");
        return build_proforma($pro, $id)->proforma;
    }

    public function preview_proforma_html(Proforma $proforma)
    {
        $html = $proforma->html;
        if ($proforma->isprint == 1) {
            if (strpos($html, '<iscopie></iscopie>') != false) {
                $html = str_replace('<iscopie></iscopie>', '<h5 style="font-weight: 500; color:red">#COPIE#</h5>', $html);
            }
        }
        return $html;
    }

    public function proforma()
    {
        return view('common.proforma');
    }

    public function proforma_show(Proforma $proforma)
    {
        return view('common.proforma-show', compact('proforma'));
    }

    function proforma_default()
    {
        $p =  proforma_dir();
        if (!count($p)) {
            return redirect(route('proforma'));
        }
        $id = getConfig('facture_zero');
        $id ??= 1;
        return redirect(route('proforma.facture', $id));
    }

    function bonentree()
    {
        $articles = Article::orderBy('article')->with('devise')->where('compte_id', compte_id())->get();
        $shop = Shop::where('compte_id', compte_id())->first();
        return view('common.bonentree', compact('articles','shop'));
    }

    function bonsortie()
    {
        $articles = Article::orderBy('article')->with('devise')->where('compte_id', compte_id())->get();
        $clients = User::where('user_role', 'client')->where('compte_id', compte_id())->get();
        $shop = Shop::where('compte_id', compte_id())->first();
        return view('common.bonsortie', compact('articles', 'clients', 'shop'));
    }
}
