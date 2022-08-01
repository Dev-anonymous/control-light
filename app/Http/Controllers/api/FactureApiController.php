<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Facture;
use App\Models\FactureSupprimee;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class FactureApiController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $factures = Facture::orderBy('id', 'desc')->where('compte_id', compte_id());
        $date = request()->date;
        $devise = request()->devise;
        $filtre = request()->filtre;
        $caissier = request()->caissier;

        $user = auth()->user();

        if ($filtre) {
            $factures = $factures->where('users_id', $user->id);
        }
        if ($caissier) {
            $factures = $factures->where('users_id', $caissier);
        }

        if ($devise) {
            $factures = $factures->where('devise', $devise);
        }
        if ($date) {
            $date = explode('-', $date);
            if (count($date) == 2) {
                $from = trim(str_replace('/', '-', $date[0]));
                $to = trim(str_replace('/', '-', $date[1]));
                $factures = $factures->where('date', '>=', $from)->whereDate('date', '<=', $to);
            }
        }
        if (auth()->user()->user_role != 'admin') {
            $factures = $factures->where('users_id', auth()->user()->id);
        }

        $tab = $tab2 = [];

        $tot = $factures;
        $factures = $factures->get(['id', 'caissier', 'client', 'total', 'date', 'devise']);

        $tot = $tot->groupBy('devise')->selectRaw('sum(total) as total, devise')->get();

        foreach ($tot as $el) {
            $a = new stdClass();
            $a->montant = montant($el->total, $el->devise);
            array_push($tab2, $a);
        }

        foreach ($factures as $el) {
            $e = (object) (array) $el->toArray();
            $e->date = $el->date->format('Y-m-d H:i:s');
            $e->total = montant($e->total, $e->devise);
            $e->numero_facture = numero_facture($e->id);
            unset($e->devise);
            array_push($tab, $e);
        }

        return $this->success([
            'factures' => $tab,
            'total' => $tab2
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Facture $facture)
    {
        if ($facture->compte_id != compte_id()) {
            abort(403);
        }
        $tab = [];

        $fac = $facture;
        $fac = (object) (array) $fac->toArray();
        $fac->date = $facture->date->format('Y-m-d H:i:s');
        $fac->total = montant($facture->total, $facture->devise);
        $fac->numero_facture = numero_facture($fac->id);

        $ventes = $facture->ventes()->get();
        foreach ($ventes as $el) {
            $a = new stdClass();
            $a->id = $el->article_id;
            $a->article = $el->article;
            $a->code = $el->code;
            $a->categorie_article = $el->categorie_article;
            $a->groupe = $el->groupe;
            $a->caissier = $el->facture->caissier;
            $a->qte = "$el->qte $el->unite_mesure";
            $a->prix = montant($el->prix, $el->devise);
            $a->total = montant($el->prix * $el->qte, $el->devise);
            $a->date = $el->facture->date->format('Y-m-d H:i:s');
            array_push($tab, $a);
        }

        return $this->success([
            'facture' => $fac,
            'articles' => $tab
        ]);
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
    public function destroy(Facture $facture)
    {
        if ($facture->compte_id != compte_id()) {
            abort(403);
        }
        if (auth()->user()->user_role == 'admin') {
            DB::transaction(function () use ($facture) {
                foreach ($facture->ventes()->get() as $vent) {
                    Article::where('id', $vent->article_id)->increment('stock', $vent->qte);
                }
                $facture->delete();
            });
        } else {
            if (auth()->user()->id != $facture->users_id) abort(403);
            DB::transaction(function () use ($facture) {
                $art = '';
                foreach ($facture->ventes()->get() as $vent) {
                    Article::where('id', $vent->article_id)->increment('stock', $vent->qte);
                    $art .= "$vent->article($vent->prix $vent->devise x $vent->qte $vent->unite_mesure),";
                }
                $art = substr($art, 0, -1);

                FactureSupprimee::create([
                    'numero_facture' => numero_facture($facture->id),
                    'client' => $facture->client,
                    'caissier' => $facture->caissier,
                    'total' => "$facture->total $facture->devise",
                    'date_facture' => $facture->date,
                    'date_suppression' => now('Africa/Lubumbashi'),
                    'articles' => $art,
                    'notifier' => 1,
                    'compte_id' => compte_id(),
                ]);
                $facture->delete();
            });
        }
    }
}
