<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleBonsortie;
use App\Models\Bonsortie;
use App\Models\Proforma;
use App\Models\Shop;
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
        // $data = Bonsortie::orderBy('id', 'desc')->with(['articles' => function ($q) {
        //     $q->orderBy('article_bonsortie.id');
        // }, 'bon_livraisons'],)->get();

        $data = Bonsortie::orderBy('id', 'desc')->where('compte_id', compte_id())->with('articles', function ($q) {
            $q->orderBy('article_bonsortie.id');
        })->get();

        $user = auth()->user();
        $tab = [];
        foreach ($data as $el) {
            $o = (object) $el->toArray();
            $o->date = $el->date?->format('d-m-Y H:i:s');
            $o->total_cdf = montant($o->total_cdf, 'CDF');
            if ($user->user_role == 'caissier') {
                if (@$el->proforma->users_id == $user->id) {
                    $tab[] = $o;
                }
            } else {
                $tab[] = $o;
            }
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
                    'client_id' => 'sometimes|',
                    'article_id' => 'required|array',
                    'article_id.*' => 'required|exists:article,id',
                    'qte' => 'required|array',
                    'qte.*' => 'required|numeric|min:1',
                    'nomclient' => 'sometimes|',
                    'adresseclient' => 'sometimes|',
                    'telephoneclient' => 'sometimes|',
                    'adresseclient' => 'sometimes|',
                    'adresselivraison' => 'sometimes|',
                    'Numerovehicule' => 'sometimes|',
                    'datelivraison' => 'sometimes|date',
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

            $user = auth()->user();

            $id = getConfig('facture_zero');
            $id ??= 1;
            $p =  proforma_dir();
            $path = $p[$id - 1];
            $pro = file_get_contents("$path/proforma");

            $shop = Shop::where('compte_id', compte_id())->first();
            $data = [
                'nom_entreprise' => @$shop->shop,
                'adresse_entreprise' => @$shop->adresse,
                'email_entreprise' => @$shop->contact,
                'telephone_entreprise' => '',
                'nom_client' => request('nomclient'),
                'adresse_client' => request('adresseclient'),
                'email_client' => '-',
                'telephone_client' => request('telephoneclient'),
                'mode_reglement' => 'CASH',
                'condition_reglement' => '-',
                'date_reglement' => now('Africa/Lubumbashi'),
                'note_reglement' => '-',
                'articles' => request('article_id'),
                'qtes' => request('qte'),
                'devise' => 'CDF'
            ];
            $proforma = build_proforma($pro, $id, $data);

            $pro = Proforma::create([
                'client_id' => request('client_id'),
                'users_id' => $user->id,
                'numero' => $proforma->numero,
                'client' => json_encode($proforma->client),
                'html' => $proforma->proforma,
                'article' => json_encode($proforma->articles),
                'montant' => "$proforma->total $proforma->devise",
                'compte_id' => compte_id(),
                'date' =>  now('Africa/Lubumbashi'),
                'enregistrer_par' => $user->name
            ]);

            $bon = Bonsortie::create([
                'proforma_id' => $pro->id,
                'total_cdf' => $tot,
                'numero' => $numerbon,
                'status' => 0,
                'emetteur' => $user->name,
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
    public function show(Bonsortie $bonsortie) {}

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
            $proforma = $bonsortie->proforma;
            if (!$proforma) {
                return $this->error("Impossible de valider ce bon, car aucune facture n'a été trouvée pour ce bon.");
            }

            $qtevente = (array) request('qtevente');
            $prixvente = (array)  request('prixvente');
            $article_ids = (array)  request('article_ids');

            if (!count($qtevente)) {
                return $this->error("Veuillez spécifier les articles vendus.");
            }

            $bonsortie->update(['status' => 1, 'valider_par' => auth()->user()->name]);
            $art = json_decode($proforma->article);


            // $alerte = [];

            // $articles = [];
            // $can = true;
            // foreach ($art as $el) {
            //     $a = Article::where('id', $el->id)->first();
            //     $candd = true;
            //     if ($a) {
            //         $oldd = $el->devise->devise;
            //         $oldp = change($el->prix, $oldd, 'CDF');

            //         $newd = $a->devise->devise;
            //         $newp = change($a->prix, $newd, 'CDF');

            //         if ($newp  != $oldp) {
            //             $alerte[] = "L'ancien prix de l'article $el->article est $oldp $oldd, le nouveau prix est : $newp $newd.";
            //             $candd = false;
            //         }

            //         if ($a->stock < $el->qte) {
            //             $can = false;
            //             $alerte[] = "Le stock actuel de l'article $el->article est de $a->stock {$a->unite_mesure->unite_mesure}, la quantité sur la facture est de : $el->qte.";
            //             $candd = false;
            //         }
            //     } else {
            //         $alerte[] = "L'article $el->article n'existe plus.";
            //         $candd = false;
            //     }

            //     if ($candd) {
            //         $a = $a->toArray();
            //         $a['qte'] = $el->qte;
            //         $a['pv'] = $el->prix;
            //         $articles[] = $a;
            //     }
            // }

            // if ($can and count($articles)) {

            $client = json_decode($proforma->client)->nom;
            $devise = explode(' ', $proforma->montant);
            $devise = trim(end($devise));
            $items = []; //json_encode($articles);


            $tot = 0;
            $dev0 = 'CDF';
            foreach ($article_ids as $k => $id) {
                $a = Article::where('id', $id)->first();

                $artfac = false;
                foreach ($art as $el) {
                    if ($id == $el->id) {
                        $artfac = $el;
                        break;
                    }
                }

                $qtev = $qtevente[$k];
                $pv = $prixvente[$k];

                if ($artfac) {
                    $qtefac = $artfac->qte;
                    if ($qtev > $qtefac) {
                        return $this->error("Erreur sur la qte vendue de l'article $a->article");
                    }
                    $pvreal = $a->prix;

                    if ($a->reduction) {
                        $prix_min = reduction($pvreal, $a->reduction);
                        if ($pv < $prix_min or $pv > $pvreal) {
                            $msg = "Le prix de vente de l'article \"$a->article\" doit etre dans la marge de réduction de $a->reduction% de $a->prix {$a->devise->devise}, c-a-d entre $prix_min {$a->devise->devise} et $a->prix {$a->devise->devise}.";
                            return $this->error($msg);
                        }
                    }
                    $tot +=  change($pv, $a->devise->devise, $dev0) * $qtev;

                    $itm = $a->toArray();
                    $itm['qte'] = $qtev;
                    $itm['pv'] = $pv;
                    $items[] = $itm;
                } else {
                    return $this->error("Erreur, un article n'a pas été trouvé sur la facture.");
                }
            }

            $tmp = [];
            foreach ($items as  $i) {
                $i = (object) $i;
                $i->devise = (object) $i->devise;
                $a = 0;
                foreach ($art as $ar) {
                    if ($ar->id == $i->id) {
                        $a = $i;
                        $a->unite_mesure = $ar->unite_mesure;
                    }
                }
                if ($a) {
                    $tmp[] = $a;
                }
            }

            $items = $tmp;

            $items = json_encode($items);
            $rq = Request::create(route('nouvelle-facture.api'), 'POST', compact('devise', 'client', 'items'));
            $resp = app()->handle($rq);

            if (200 == $resp->getStatusCode()) {
                $body = json_decode($resp->getContent());

                if ($body->success) {
                    $proforma->update(['date_encaissement' => now('Africa/Lubumbashi'), 'article' => $items, 'montant' => "$tot $dev0"]);

                    // FLEMME , ON DOIT RECREE LE PROFORMAT AVEC LES NOUVELS ARTICLES VENDUS
                    // PROFORMA SHOW, GARDE LES ANCIENNES DONNES LORS DE LA CREARION DU BON DE SORTIE

                    // $data = [
                    //     'nom_entreprise' => @$shop->shop,
                    //     'adresse_entreprise' => @$shop->adresse,
                    //     'email_entreprise' => @$shop->contact,
                    //     'telephone_entreprise' => '',
                    //     'nom_client' => request('nomclient'),
                    //     'adresse_client' => request('adresseclient'),
                    //     'email_client' => '-',
                    //     'telephone_client' => request('telephoneclient'),
                    //     'mode_reglement' => 'CASH',
                    //     'condition_reglement' => '-',
                    //     'date_reglement' => now('Africa/Lubumbashi'),
                    //     'note_reglement' => '-',
                    //     'articles' => request('article_id'),
                    //     'qtes' => request('qte'),
                    //     'devise' => 'CDF'
                    // ];
                    // $proforma = build_proforma($pro, $id, $data);


                    ArticleBonsortie::where('bonsortie_id', $bonsortie->id)->delete();
                    $bonsortie->update((['total_cdf' => $tot]));
                    foreach ($tmp as $i => $el) {
                        $article = Article::where('id', $el->id)->first();
                        ArticleBonsortie::create([
                            'article_id' => $el->id,
                            'bonsortie_id' => $bonsortie->id,
                            'article' => ucfirst($article->article),
                            'prix_vente' => $el->pv,
                            'devise_vente' => $article->devise->devise,
                            'qte' => $el->qte,
                        ]);
                    }
                } else {
                    $msg = $body->data->msg;
                    $msg = implode('<br>', $msg);
                    $msg = "$body->message<br>$msg";
                    return $this->error($msg);
                }
            } else {
                return $this->error("Une erreur s'est produite, veuillez reessayer.");
            }
            // } else {
            //     $t = ["Impossible d'encaisser cette facture", ...$alerte];
            //     $m = implode('<br>', $t);
            //     return $this->error($m);
            // }

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
