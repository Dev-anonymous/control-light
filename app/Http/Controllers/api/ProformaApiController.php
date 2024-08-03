<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleBonsortie;
use App\Models\BonLivraison;
use App\Models\Bonsortie;
use App\Models\Proforma;
use App\Models\User;
use App\Traits\ApiResponser;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProformaApiController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date = request()->date;
        $devise = request()->devise;

        $pro = Proforma::where('compte_id', compte_id())->orderBy('id', 'desc');

        if ($date) {
            $date = explode('-', $date);
            if (count($date) == 2) {
                $from = trim(str_replace('/', '-', $date[0]));
                $to = trim(str_replace('/', '-', $date[1]));
                $pro = $pro->whereDate('date', '>=', $from)->whereDate('date', '<=', $to);
            }
        }

        if ($devise) {
            $pro = $pro->where('montant', 'like', "%$devise%");
        }

        $user = auth()->user();

        if ($user->user_role == 'caissier') {
            $pro = $pro->where('users_id', $user->id);
        }

        $pro = $pro->get();

        $t = [];
        foreach ($pro as $el) {
            $e = (object) $el->toArray();
            $e->date = $el->date->format('Y-m-d H:i:s');
            if ($e->date_encaissement) {
                $e->date_encaissement = $el->date_encaissement->format('Y-m-d H:i:s');
            }
            $cl = json_decode($el->client);
            $e->client = "$cl->nom ($cl->tel), $cl->email <br>$cl->adresse";
            $t[] = $e;
        }

        return $this->success($t);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        demo();
        $rules = [
            'nom_entreprise' => 'required',
            'adresse_entreprise' => 'required',
            'email_entreprise' => 'required',
            'telephone_entreprise' => 'required',
            'nom_client' => 'required',
            'adresse_client' => 'required',
            'email_client' => 'sometimes',
            'telephone_client' => 'sometimes',
            'mode_reglement' => 'sometimes',
            'condition_reglement' => 'sometimes',
            'date_reglement' => 'sometimes',
            'note_reglement' => 'sometimes',
            'articles' => 'required|array',
            'articles.*' => 'required|exists:article,id',
            'qtes' => 'required|array',
            'qtes.*' => 'min:1',
            'devise' => 'required|in:CDF,USD',
            'proforma_id' => 'required',
            'client_id' => 'required',
        ];
        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            $m = implode('<br>', $validator->errors()->all());
            return $this->error($m);
        }

        $id = request()->proforma_id;
        $p =  proforma_dir();
        if ($id > count($p)) {
            abort(404);
        }
        $path = $p[$id - 1];
        $pro = file_get_contents("$path/proforma");
        $proforma = build_proforma($pro, $id);

        if (!$proforma->total) {
            return $this->error("Veuillez ajouter un article à la facture.");
        }

        $user = auth()->user();

        DB::beginTransaction();
        $artTab = [];
        $article_ids = request('articles');
        $qte = request('qtes');

        $tot = 0;
        foreach ($article_ids as $i => $el) {
            $article = Article::where('id', $el)->first();
            if ($article->stock < $qte[$i]) {
                return $this->error("Le stock de l'article '{$article->article}' est de $article->stock, modifiez la qte sur votre bon SVP.");
            }
            $tot += change($article->prix, $article->devise->devise, 'CDF') * $qte[$i];
            $artTab[] = $article;
        }

        $pro = Proforma::create([
            'users_id' => $user->id,
            'numero' => $proforma->numero,
            'client' => json_encode($proforma->client),
            'html' => $proforma->proforma,
            'article' => json_encode($proforma->articles),
            'montant' => "$proforma->total $proforma->devise",
            'compte_id' => compte_id(),
            'date' =>  now('Africa/Lubumbashi'),
            'enregistrer_par' => auth()->user()->name
        ]);

        $numerbon = numbon('sortie');
        $bon = Bonsortie::create([
            'proforma_id' => $pro->id,
            'total_cdf' => $tot,
            'numero' => $numerbon,
            'status' => 0,
            'emetteur' => auth()->user()->name,
            'date' => now('Africa/Lubumbashi'),
            'compte_id' => compte_id(),
            'type' => 'article'
        ]);

        foreach ($artTab as $i => $article) {
            ArticleBonsortie::create([
                'article_id' => $article->id,
                'bonsortie_id' => $bon->id,
                'article' => ucfirst($article->article),
                'prix_vente' => $article->prix,
                'devise_vente' => $article->devise->devise,
                'qte' => $qte[$i],
            ]);
        }

        $client = User::where('id', request('client_id'))->first();
        BonLivraison::create([
            'bonsortie_id' => $bon->id,
            'nomclient' => $client->name,
            'telephoneclient' => $client->phone,
            'adresseclient' => $client->adresse,
            'adresselivraison' => $client->adresselivraison,
            'chauffeur' => '', // request('chauffeur'),
            'numerovehicule' => '', // request('numerovehicule'),
            'datelivraison' => now('Africa/Lubumbashi'), // request('datelivraison'),
        ]);
        DB::commit();

        return $this->success(['numero_facture' => $proforma->numero], "Votre facture a été créée avec succès.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proforma $proforma)
    {
        demo();
        abort_if($proforma->compte_id != compte_id(), 403, "uhm");
        $proforma->delete();
        return $this->success([], "Facture supprimée");
    }

    public function encaissement(Proforma $proforma)
    {
        demo();
        if ($proforma->date_encaissement != null) {
            return $this->error("Cette facture est déjà encaissée.");
        }
        $art = json_decode($proforma->article);
        $alerte = [];

        $articles = [];
        $can = true;
        foreach ($art as $el) {
            $a = Article::where('id', $el->id)->first();
            $candd = true;
            if ($a) {
                $oldd = $el->devise->devise;
                $oldp = change($el->prix, $oldd, 'CDF');

                $newd = $a->devise->devise;
                $newp = change($a->prix, $newd, 'CDF');

                if ($newp  != $oldp) {
                    $alerte[] = "L'ancien prix de l'article $el->article est $oldp $oldd, le nouveau prix est : $newp $newd.";
                    $candd = false;
                }

                if ($a->stock < $el->qte) {
                    $can = false;
                    $alerte[] = "Le stock actuel de l'article $el->article est de $a->stock {$a->unite_mesure->unite_mesure}, la quantité sur la facture est de : $el->qte.";
                    $candd = false;
                }
            } else {
                $alerte[] = "L'article $el->article n'existe plus.";
                $candd = false;
            }

            if ($candd) {
                $a = $a->toArray();
                $a['qte'] = $el->qte;
                $a['pv'] = $el->prix;
                $articles[] = $a;
            }
        }

        if ($can and count($articles)) {
            $client = json_decode($proforma->client)->nom;
            $devise = explode(' ', $proforma->montant);
            $devise = trim(end($devise));
            $items = json_encode($articles);

            $rq = Request::create(route('nouvelle-facture.api'), 'POST', compact('devise', 'client', 'items'));
            $resp = app()->handle($rq);

            if (200 == $resp->getStatusCode()) {
                $body = json_decode($resp->getContent());

                if ($body->success) {
                    $proforma->update(['date_encaissement' => now('Africa/Lubumbashi')]);
                    return $this->success($body->data, $body->message);
                } else {
                    $msg = $body->data->msg;
                    $msg = implode('<br>', $msg);
                    $msg = "$body->message<br>$msg";
                    return $this->error($msg);
                }
            } else {
                return $this->error("Une erreur s'est produite, veuillez reessayer.");
            }
        } else {
            $t = ["Impossible d'encaisser cette facture", ...$alerte];
            $m = implode('<br>', $t);
            return $this->error($m);
        }
    }

    function print()
    {
        $id = request('id');
        $success = false;
        $pro = Proforma::where('id', $id)->first();
        if ($pro) {
            if ($pro->isprint != 1) {
                $pro->update(['isprint' => 1]);
            }
            $success = true;
        }
        return response(['success' => $success]);
    }
}
