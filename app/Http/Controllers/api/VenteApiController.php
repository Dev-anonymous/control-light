<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Facture;
use App\Models\Vente;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use stdClass;

class VenteApiController extends Controller
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
        $categorie = request()->categorie;
        $groupe = request()->groupe;
        $devise = request()->devise;

        $ventesTot = Vente::orderBy('id', 'desc')->where('compte_id', compte_id());

        if ($devise) {
            $ventesTot = $ventesTot->where('devise', $devise);
        }
        if (!$categorie and $groupe) {
            $ventesTot = $ventesTot->where('groupe_article_id', $groupe);
        } else {
            if ($categorie) {
                $ventesTot = $ventesTot->where('categorie_article_id', $categorie);
            }
        }

        if ($date) {
            $date = explode('-', $date);
            if (count($date) == 2) {
                $from = trim(str_replace('/', '-', $date[0]));
                $to = trim(str_replace('/', '-', $date[1]));
                $ventesTot = $ventesTot->whereHas('facture', function ($query) use ($from, $to) {
                    return $query->where('date', '>=', $from)->whereDate('date', '<=', $to);
                });
            }
        }

        if (auth()->user()->user_role != 'admin') {
            $ventesTot = $ventesTot->whereHas('facture', function ($query) use ($from, $to) {
                return $query->where('users_id', auth()->user()->id);
            });
        }

        $tot = $ventesTot;
        $ventesTot = $ventesTot->get();

        $tot = $tot->groupBy('devise')->selectRaw('sum(qte*prix) as total, devise')->get();

        $tab = [];
        $tab2 = [];
        foreach ($ventesTot as $el) {
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

        foreach ($tot as $el) {
            $a = new stdClass();
            $a->montant = montant($el->total, $el->devise);
            array_push($tab2, $a);
        }

        return $this->success([
            'ventes' => $tab,
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
    public function show(Article $vente)
    {
        if ($vente->compte_id != compte_id()) {
            abort(403);
        }
        $date = request()->date;
        $devise = request()->devise;

        $article = $vente;
        $ventesTot = $article
            ->ventes()
            ->orderBy('id', 'desc');

        if ($devise) {
            $ventesTot = $ventesTot->where('devise', $devise);
        }
        if ($date) {
            $date = explode('-', $date);
            if (count($date) == 2) {
                $from = trim(str_replace('/', '-', $date[0]));
                $to = trim(str_replace('/', '-', $date[1]));
                $ventesTot = $ventesTot->whereHas('facture', function ($query) use ($from, $to) {
                    return $query->where('date', '>=', $from)->whereDate('date', '<=', $to);
                });
            }
        }

        $tot = $ventesTot;
        $ventesTot = $ventesTot->get();

        $tot = $tot->groupBy('devise')->selectRaw('sum(qte*prix) as total, devise')->get();

        $tab = [];
        $tab2 = [];
        foreach ($ventesTot as $el) {
            $a = new stdClass();
            $a->caissier = $el->facture->caissier;;
            $a->qte = "$el->qte $el->unite_mesure";
            $a->prix = montant($el->prix, $el->devise);
            $a->total = montant($el->prix * $el->qte, $el->devise);
            $a->date = $el->facture->date->format('Y-m-d H:i:s');
            array_push($tab, $a);
        }

        foreach ($tot as $el) {
            $a = new stdClass();
            $a->montant = montant($el->total, $el->devise);
            array_push($tab2, $a);
        }

        return $this->success([
            'ventes' => $tab,
            'total' => $tab2
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Article $vente)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
