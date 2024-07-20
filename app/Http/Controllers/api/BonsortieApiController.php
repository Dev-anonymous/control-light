<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleBonsortie;
use App\Models\BonLivraison;
use App\Models\Bonsortie;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BonsortieApiController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Bonsortie::orderBy('id', 'desc')->with(['articles', 'bon_livraisons'])->get();
        $tab = [];
        foreach ($data as $el) {
            $o = (object) $el->toArray();
            $o->date = $el->date?->format('d-m-Y H:i:s');
            $o->total_cdf = montant($o->total_cdf, 'CDF');
            $tab[] = $o;
        }

        return $this->success($tab);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (request('typebon') == 'article') {
            $validator = Validator::make(
                $request->all(),
                [
                    'article_id' => 'required|array',
                    'article_id.*' => 'required|exists:article,id',
                    'qte' => 'required|array',
                    'qte.*' => 'required|numeric|min:1',
                    'nomclient' => 'required|string',
                    'adresseclient' => 'sometimes|string',
                    'telephoneclient' => 'required|string',
                    'adresseclient' => 'sometimes|string',
                    'adresselivraison' => 'required|string',
                    'Numerovehicule' => 'sometimes|string',
                    'datelivraison' => 'required|date',
                    'motif' => 'sometimes|',
                ]
            );

            if ($validator->fails()) {
                return $this->error(implode(' ', $validator->errors()->all()));
            }

            $tot = 0;
            $article_ids = request('article_id');
            $qte = request('qte');
            foreach ($article_ids as $i => $el) {
                $article = Article::where('id', $el)->first();
                if ($article->stock < $qte[$i]) {
                    return $this->error("Le stock de l'article '{$article->article}' est de $article->stock, modifiez la qte sur votre bon SVP.");
                }
                $tot += change($article->prix, $article->devise->devise, 'CDF') * $qte[$i];
            }

            $numerbon = numbon('sortie');
            DB::beginTransaction();
            $bon = Bonsortie::create([
                'total_cdf' => $tot,
                'numero' => $numerbon,
                'status' => 0,
                'emetteur' => auth()->user()->name,
                'date' => now('Africa/Lubumbashi'),
                'compte_id' => compte_id(),
                'type' => 'article'
            ]);

            foreach ($article_ids as $i => $el) {
                $article = Article::where('id', $el)->first();
                ArticleBonsortie::create([
                    'article_id' => $el,
                    'bonsortie_id' => $bon->id,
                    'article' => ucfirst($article->article),
                    'prix_vente' => $article->prix,
                    'devise_vente' => $article->devise->devise,
                    'qte' => $qte[$i],
                ]);
            }
            BonLivraison::create([
                'bonsortie_id' => $bon->id,
                'nomclient' => request('nomclient'),
                'telephoneclient' => request('telephoneclient'),
                'adresseclient' => request('adresseclient'),
                'adresselivraison' => request('adresselivraison'),
                'chauffeur' => request('chauffeur'),
                'numerovehicule' => request('numerovehicule'),
                'datelivraison' => request('datelivraison'),
            ]);
            DB::commit();
            return $this->success(null, "Le bon de sortie $numerbon a été créé, veuiller attendre la validation du gérant.");
        } else {
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bonsortie  $bonsortie
     * @return \Illuminate\Http\Response
     */
    public function show(Bonsortie $bonsortie)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bonsortie  $bonsortie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bonsortie $bonsortie)
    {
        if ($bonsortie->status != 0) {
            abort(403);
        }
        $action = request('action');
        if ($action == 'valider') {
            DB::beginTransaction();
            $bonsortie->update(['status' => 1, 'valider_par' => auth()->user()->name]);
            DB::commit();
            return $this->success([], "Le bon {$bonsortie->numero} a été validé avec succès.");
        } else if ($action = 'rejeter') {
            DB::beginTransaction();
            $bonsortie->update(['status' => 2, 'rejeter_par' => auth()->user()->name]);
            DB::commit();
            return $this->success([], "Le bon {$bonsortie->numero} a été rejeté avec succès.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bonsortie  $bonsortie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bonsortie $bonsortie)
    {
        abort_if(!in_array(auth()->user()->user_role, ['admin', 'gerant']), 403);
        $bonsortie->delete();
        return $this->success([], "Le bon {$bonsortie->numero} a été supprimé.");
    }
}
