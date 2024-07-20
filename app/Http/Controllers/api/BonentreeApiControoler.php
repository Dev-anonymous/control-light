<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Approvisionnement;
use App\Models\Article;
use App\Models\ArticleBonentree;
use App\Models\Bonentree;
use App\Models\Devise;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BonentreeApiControoler extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Bonentree::orderBy('id', 'desc')->with('articles')->get();
        $tab = [];
        foreach ($data as $el) {
            $o = (object) $el->toArray();
            $o->date = $el->date?->format('d-m-Y H:i:s');
            $o->total_cdf = montant($o->total_cdf, 'CDF');
            $tab[] = $o;
        }

        return $this->success($tab);
    }

    function totbon()
    {
        $type = request('type');
        $article_ids = (array)  request('article_id');
        $qte = (array) request('qte');
        $prix_achat = (array) request('prix_achat');
        $devise_achat = (array) request('devise_achat');
        // $prix_vente = request('prix_vente');
        // $devise_vente = request('devise_vente');

        if ($type == 'entree') {
            $tot = 0;
            foreach ($prix_achat as $i => $p) {
                $tot += change($p, $devise_achat[$i], 'CDF') * $qte[$i];
            }
            $tot = montant($tot, 'CDF');
            return response(['total' => $tot]);
        }
        if ($type == 'sortie') {
            $tot = 0;
            foreach ($article_ids as $i => $p) {
                $article = Article::where('id', $p)->first();
                $tot += change($article->prix, $article->devise->devise, 'CDF') * $qte[$i];
            }
            $tot = montant($tot, 'CDF');
            return response(['total' => $tot]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'article_id' => 'required|array',
                'article_id.*' => 'required|exists:article,id',
                'qte' => 'required|array',
                'qte.*' => 'required|numeric|min:1',
                'prix_achat' => 'required|array',
                'prix_achat.*' => 'required|numeric|min:0.1',
                'prix_vente' => 'required|array',
                'prix_vente.*' => 'required|numeric|min:0.1',
                'devise_achat' => 'required|array',
                'devise_achat.*' => 'required|in:CDF,USD',
                'devise_vente' => 'required|array',
                'devise_vente.*' => 'required|in:CDF,USD',
                // date expiration !!! flem ....
            ]
        );

        if ($validator->fails()) {
            return $this->error(implode(' ', $validator->errors()->all()));
        }

        $article_ids = request('article_id');
        $qte = (array) request('qte');
        $prix_achat = (array) request('prix_achat');
        $devise_achat = (array) request('devise_achat');
        $prix_vente = request('prix_vente');
        $devise_vente = request('devise_vente');

        $tot = 0;
        foreach ($prix_achat as $i => $p) {
            $tot += change($p, $devise_achat[$i], 'CDF') * $qte[$i];
        }

        $numerbon = numbon();
        DB::beginTransaction();
        $bon = Bonentree::create([
            'total_cdf' => $tot,
            'numero' => $numerbon,
            'status' => 0,
            'emetteur' => auth()->user()->name,
            'date' => now('Africa/Lubumbashi'),
            'compte_id' => compte_id()
        ]);

        foreach ($article_ids as $i => $el) {
            $article = Article::where('id', $el)->first();
            ArticleBonentree::create([
                'article_id' => $el,
                'bonentree_id' => $bon->id,
                'article' => ucfirst($article->article),
                'prix_achat' => $prix_achat[$i],
                'devise_achat' => $devise_achat[$i],
                'prix_vente' => $prix_vente[$i],
                'devise_vente' => $devise_vente[$i],
                'qte' => $qte[$i],
            ]);
        }

        DB::commit();

        return $this->success(null, "Le bon d'entré $numerbon a été créé, veuiller attendre la validation du gérant.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bonentree  $bonentree
     * @return \Illuminate\Http\Response
     */
    public function show(Bonentree $bonentree)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bonentree  $bonentree
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bonentree $bonentree)
    {
        if ($bonentree->status != 0) {
            abort(403);
        }
        $action = request('action');
        if ($action == 'valider') {
            DB::beginTransaction();
            $bonentree->update(['status' => 1, 'valider_par' => auth()->user()->name]);

            $items = ArticleBonentree::where('bonentree_id', $bonentree->id)->get();
            foreach ($items as $el) {
                $article = Article::where('id', $el->article_id)->first();
                $article->update($e = [
                    'stock' => $article->stock + $el->qte,
                    'prix_achat' => $el->prix_achat,
                    'devise_achat' => $el->devise_achat,
                    'prix' => $el->prix_vente,
                    'devise_id' => Devise::where('devise', $el->devise_vente)->first()->id,
                ]);
                Approvisionnement::create([
                    'article_id' => $article->id,
                    'qte' => $el->qte,
                    'date' => now('Africa/Lubumbashi'),
                    'compte_id' => compte_id()
                ]);
            }
            DB::commit();
            return $this->success([], "Le bon {$bonentree->numero} a été validé avec succès. Le stock, le prix d'achat et vente des articles correspondants ont été également mis à jour.  ");
        } else if ($action = 'rejeter') {
            DB::beginTransaction();
            $bonentree->update(['status' => 2, 'rejeter_par' => auth()->user()->name]);
            DB::commit();
            return $this->success([], "Le bon {$bonentree->numero} a été rejeté avec succès.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bonentree  $bonentree
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bonentree $bonentree)
    {
        abort_if(!in_array(auth()->user()->user_role, ['admin', 'gerant']), 403);
        $bonentree->delete();
        return $this->success([], "Le bon {$bonentree->numero} a été supprimé.");
    }
}
