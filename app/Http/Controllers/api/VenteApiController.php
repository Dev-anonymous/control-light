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
        $groupall = request()->groupall;

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

        if (!in_array(auth()->user()->user_role, ['admin', 'gerant'])) {
            $ventesTot = $ventesTot->whereHas('facture', function ($query) {
                return $query->where('users_id', auth()->user()->id);
            });
        }

        $marge = clone $ventesTot;
        $marge = $marge->selectRaw('sum(marge_cdf) as marge_cdf, sum(marge_usd) as marge_usd')->get();
        $marge = [
            'cdf' => montant($marge[0]->marge_cdf, 'CDF'),
            'usd' => montant($marge[0]->marge_usd, 'USD'),
        ];

        if ($groupall) {
            $tot = $ventesTot;
            $ventesTot = $ventesTot->groupBy('article_id')->selectRaw('*,sum(qte) as qtevendue, sum(marge_cdf) marge_cdf, sum(marge_usd) marge_usd')->get();
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
                $stock = '-';
                if ($el->article()->first()) {
                    $stock = $el->article()->first()->stock;
                }
                $a->stock = "$stock $el->unite_mesure";
                $a->qte = "$el->qtevendue $el->unite_mesure";
                $a->prix = montant($el->prix, $el->devise);
                $a->total = montant($el->prix * $el->qtevendue, $el->devise);
                $a->marge = $el->devise == 'USD' ? montant($el->marge_usd, $el->devise) : montant($el->marge_cdf, $el->devise);
                $a->marge_result = $el->marge_cdf == 0 ? 'solde' : ($el->marge_cdf > 0 ? 'gain' : 'perte');
                array_push($tab, $a);
            }

            $tm = [];
            $tot = $tot->toArray();
            foreach (['USD', 'CDF'] as $d) {
                $tv = 0;
                foreach ($tot as $t) {
                    if ($t['devise'] == $d) {
                        $tv += $t['total'];
                    }
                }
                $t['total'] = $tv;
                array_push($tm, (object) ['total' => $tv, 'devise' => $d]);
            }
            $tot = $tm;

            foreach ($tot as $el) {
                $a = new stdClass();
                $a->montant = montant($el->total, $el->devise);
                array_push($tab2, $a);
            }
        } else {
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
                $a->marge = $el->devise == 'USD' ? montant($el->marge_usd, $el->devise) : montant($el->marge_cdf, $el->devise);
                $a->marge_result = $el->marge_cdf == 0 ? 'solde' : ($el->marge_cdf > 0 ? 'gain' : 'perte');
                array_push($tab, $a);
            }

            foreach ($tot as $el) {
                $a = new stdClass();
                $a->montant = montant($el->total, $el->devise);
                array_push($tab2, $a);
            }
        }

        return $this->success([
            'ventes' => $tab,
            'total' => $tab2,
            'marge' => $marge,
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
