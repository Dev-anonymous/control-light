<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\FactureSupprimee;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function compte()
    {
        return view('admin.compte');
    }

    public function groupe_article()
    {
        return view('admin.groupe-article');
    }

    public function categorie_article()
    {
        return view('admin.categorie-article');
    }

    public function unite_mesure()
    {
        return view('admin.unite-mesure');
    }

    public function cassier()
    {
        return view('admin.cassier');
    }

    public function articles()
    {
        return view('admin.articles');
    }

    public function code_barre()
    {
        return view('admin.code-barre');
    }

    public function article_detail(Article $article)
    {
        if ($article->compte_id != compte_id()) {
            abort(403);
        }
        return view('admin.detail-article', compact('article'));
    }

    public function ventesMagasin()
    {
        return view('admin.ventes-magasin');
    }

    public function ventes()
    {
        return view('admin.ventes');
    }

    public function etatMagasin()
    {
        return view('admin.etat-magasin');
    }

    public function devise()
    {
        return view('admin.devise');
    }

    public function facture_sup()
    {
        $factures = FactureSupprimee::orderBy('id', 'desc')->where('compte_id', compte_id())->get();
        return view('admin.facture-supprimee', compact('factures'));
    }
}
