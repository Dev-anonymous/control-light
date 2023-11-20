<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Devise;
use App\Models\Facture;
use App\Models\FactureSupprimee;
use App\Models\Vente;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use stdClass;

class DataApiController extends Controller
{
    use ApiResponser;

    public function netApayer()
    {
        $items = request()->items;
        $items = @json_decode($items);
        $devise = request()->devise;

        if (!in_array($devise, ['CDF', 'USD'])) return $this->error("Devise non valide : $devise");

        $tot = 0;
        $erro = false;
        $msg = "";

        if (is_array($items)) {
            foreach ($items as $e) {
                try {
                    $article = Article::where('id', $e->id)->where('compte_id', compte_id())->first();
                    if ($article) {
                        $pv = str_replace(['USD', 'CDF', ' '], '', $e->pv);
                        if (!is_numeric($pv)) {
                            $erro = true;
                            $msg = "Le prix de vente $pv semble etre invalide";
                            break;
                        }
                        $prix_min = reduction($article->prix, $article->reduction);
                        if ($pv < $prix_min or $pv > $article->prix) {
                            $erro = true;
                            $msg = "Le prix de vente de l'article \"$article->article\" doit etre dans la marge de réduction de $article->reduction% de $article->prix {$article->devise->devise}, c-a-d entre $prix_min {$article->devise->devise} et $article->prix {$article->devise->devise}.";
                            break;
                        }
                        $tot +=  change($pv, $article->devise->devise, $devise) * $e->qte;
                    } else {
                        $erro = true;
                        $msg = "Certains articles sur votre facture n'ont pas été retrouvés.";
                    }
                } catch (\Throwable $th) {
                    $erro = true;
                    $msg = "Données non valides.";
                    break;
                }
            }
        }
        if ($erro) return $this->error($msg);

        $tot = montant($tot, $devise);
        return $this->success([
            'total' => $tot
        ]);
    }

    public function afficherFacture()
    {
        $items = request()->items;
        $items = @json_decode($items);
        $devise = request()->devise;

        if (!in_array($devise, ['CDF', 'USD'])) return $this->error("Devise non valide : $devise");

        $tot = 0;
        $tab = [];

        if (is_array($items)) {
            foreach ($items as $e) {
                $article = Article::where('id', @$e->id)->where('compte_id', compte_id())->first();
                if ($article) {
                    $pv = str_replace(['USD', 'CDF', ' '], '', $e->pv);
                    if (!is_numeric($pv)) {
                        $msg = "Le prix de vente $pv semble etre invalide";
                        return $this->error($msg);
                    }
                    $prix_min = reduction($article->prix, $article->reduction);
                    if ($pv < $prix_min or $pv > $article->prix) {
                        $msg = "Le prix de vente de l'article \"$article->article\" doit etre dans la marge de réduction de $article->reduction% de $article->prix {$article->devise->devise}, c-a-d entre $prix_min {$article->devise->devise} et $article->prix {$article->devise->devise}.";
                        return $this->error($msg);
                    }

                    $tot +=  change($pv, $article->devise->devise, $devise) * (int) @$e->qte;
                    $a = new stdClass();
                    $a->article = $article->article;
                    $a->qte = @$e->qte . " {$article->unite_mesure->unite_mesure}";
                    $a->prix = montant($pv, $article->devise->devise);
                    $a->total = montant($pv * (int) @$e->qte, $article->devise->devise);
                    array_push($tab, $a);
                } else {
                    return $this->error("Un article n'a pas été retrouvé! nous essayons de mettre à jour votre liste d'articles.");
                }
            }
        } else {
            return $this->error("Données non valides");
        }
        $tot = montant($tot, $devise);

        return $this->success([
            'facture' => (object) ['total' => $tot, 'caissier' => auth()->user()->name],
            'articles' => $tab
        ]);
    }

    public function checkItems()
    {
        $items = request()->items;
        $items = @json_decode($items);

        $valid = [];

        if (is_array($items)) {
            foreach ($items as $e) {
                $article = Article::where('id', @$e->id)->where('compte_id', compte_id())->first();
                if ($article) {
                    $pv = (float) str_replace(['USD', 'CDF', ' '], '', @$e->pv);
                    $a = new stdClass();
                    $a->id = $article->id;
                    $a->article = $article->article;
                    $a->qte = (int) @$e->qte;
                    $a->prix = montant($article->prix, $article->devise->devise);
                    $prix_min = reduction($article->prix, $article->reduction);
                    if ($pv < $prix_min or $pv > $article->prix) {
                        $pv = $a->prix;
                    }
                    $a->pv = $pv;
                    $a->prix_min = $prix_min;
                    $a->reduction = $article->reduction;
                    array_push($valid, $a);
                }
            }
        } else {
            return $this->error("Données non valides");
        }
        return $this->success($valid);
    }

    public function nouvelle_facture()
    {
        demo();
        $items = @json_decode(request()->items);
        $devise = strtoupper(request()->devise);
        $client = request()->client;

        if (!in_array($devise, ['CDF', 'USD'])) return $this->error("Devise non valide : $devise");

        $error = [];
        $tabA = [];
        $tot = 0;

        $can = true;

        if (is_array($items)) {
            if (!count($items)) {
                return $this->error("Aucun article à enregistrer.");
            }
            foreach ($items as $e) {
                $id = (int) @$e->id;
                $max = date('Y-m-d', strtotime('+30 days'));
                $article = Article::where('id', $id)->where('compte_id', compte_id())->whereNull('date_expiration')->orWhere(function ($query) use ($max, $id) {
                    $query->where('id', $id);
                    $query->whereNotNull('date_expiration')->whereDate('date_expiration', '>=', $max);
                })->first();

                if ($article and (int) @$e->qte > 0) {
                    if ($article->stock >= $e->qte) {
                        $pv = (float) str_replace(['USD', 'CDF', ' '], '', @$e->pv);
                        $prix_min = reduction($article->prix, $article->reduction);
                        if ($pv < $prix_min or $pv > $article->prix) {
                            $msg = "Le prix de vente de l'article \"$article->article\" doit etre dans la marge de réduction de $article->reduction% de $article->prix {$article->devise->devise}, c-a-d entre $prix_min {$article->devise->devise} et $article->prix {$article->devise->devise}.";
                            $can = false;
                            array_push($error, $msg);
                            continue;
                        }

                        $tot +=  change($pv, $article->devise->devise, $devise) * $e->qte;

                        $a = new stdClass();
                        $a->ida = $article->id;
                        $a->article = $article->article;
                        $a->code = $article->code;
                        $a->categorie_article = $article->categorie_article->categorie;
                        $a->categorie_article_id = $article->categorie_article->id;
                        $a->groupe_article_id = $article->categorie_article->groupe_article->id;
                        $a->groupe = $article->categorie_article->groupe_article->groupe;
                        $a->qte = $e->qte;
                        $a->prix = $pv;
                        $a->devise = $article->devise->devise;
                        $a->unite_mesure = $article->unite_mesure->unite_mesure;
                        array_push($tabA, $a);
                    } else {
                        $can = false;
                        array_push($error, "Stock disponible pour \"$article->article\" : $article->stock {$article->unite_mesure->unite_mesure}");
                    }
                } else {
                    array_push($error, "Article non trouvé : " . @$e->article . " [ou problème de date d'expiration <= 30 jours]");
                    $can = false;
                }
            }
        } else {
            return $this->error("Données non valides");
        }

        if (!$can) {
            return $this->error("Impossible d'enregistrer cette facture", ['msg' => $error]);
        }

        $data['articles'] = $tabA;
        $data['client'] = $client ?? '-';
        $data['total'] = $tot;
        $data['devise'] = strtoupper($devise);

        $rep = DB::transaction(function () use ($data) {
            $article = $data['articles'];
            $d = now('Africa/Lubumbashi');
            $fac =  Facture::create([
                'numero_facture' => numero_facture(),
                'users_id' => auth()->user()->id,
                'client' => $data['client'],
                'caissier' => auth()->user()->name,
                'total' => $data['total'],
                'devise' => $data['devise'],
                'date' => $d,
                'compte_id' => compte_id()
            ]);
            foreach ($article as $e) {
                $v = (array) $e;
                $v['facture_id'] = $fac->id;
                $v['article_id'] = $e->ida;
                $v['compte_id'] = compte_id();
                Vente::create($v);
                Article::where('id', $v['ida'])->decrement('stock', $v['qte']);
            }
            return [$fac, $d->format('Y-m-d H:i:s')];
        });
        $d = $rep[1];
        $num = $rep[0]->numero_facture;
        $tot = montant($tot, $devise);

        return $this->success(['numero_facture' => $num, 'date' => $d], "Facture $num enregistrée, pour un total de $tot, $d");
    }

    public function taux()
    {
        demo();
        $validator = Validator::make(request()->all(), ['cdf_usd' => 'required|numeric|min:0.000001', 'usd_cdf' => 'required|numeric|min:0.000001']);

        if ($validator->fails()) {
            return $this->error('Erreur de validation', ['msg' => $validator->errors()->all()]);
        }

        $data = $validator->validated();

        $cdf_usd = $data['cdf_usd'];
        $usd_cdf = $data['usd_cdf'];

        Devise::where('devise', 'CDF')->first()->tauxes()->where('compte_id', compte_id())->first()->update(['taux' => $cdf_usd]);
        Devise::where('devise', 'USD')->first()->tauxes()->where('compte_id', compte_id())->first()->update(['taux' => $usd_cdf]);
        return $this->success('', "Taux mis à jour.");
    }

    public function statistique()
    {
        $devise = request()->devise;
        $caissier = request()->caissier;

        $tab = [];
        $total = [];
        if ($devise) {
            $sum = [];
            $t = 0;
            foreach (range(1, 12) as $mois) {
                $factures = Facture::where('devise', $devise)->where('compte_id', compte_id());
                $factures = $factures->whereMonth('date', $mois);

                if ($caissier) {
                    $factures = $factures->where('users_id', $caissier);
                }

                if (auth()->user()->user_role != 'admin') {
                    $factures = $factures->where('users_id', auth()->user()->id);
                }

                $factures = $factures->selectRaw('coalesce(sum(total), 0) as total');
                $factures = $factures->get();
                $tot = $factures[0]->total;
                $t += $tot;
                array_push($sum, $tot);
            }
            $tab[$devise] = $sum;
            array_push($total, montant($t, $devise));
        } else {
            foreach (['CDF', 'USD'] as $dev) {
                $sum = [];
                $t = 0;
                foreach (range(1, 12) as $mois) {
                    $factures = Facture::where('devise', $dev)->where('compte_id', compte_id());
                    $factures = $factures->whereMonth('date', $mois);

                    if ($caissier) {
                        $factures = $factures->where('users_id', $caissier);
                    }

                    if (auth()->user()->user_role != 'admin') {
                        $factures = $factures->where('users_id', auth()->user()->id);
                    }
                    $factures = $factures->selectRaw('coalesce(sum(total), 0) as total');
                    $factures = $factures->get();
                    $tot = $factures[0]->total;
                    $t += $tot;
                    array_push($sum, $tot);
                }
                $tab[$dev] = $sum;
                array_push($total, montant($t, $dev));
            }
        }

        return $this->success(['stat' => $tab, 'total' => $total]);
    }

    public function daccord()
    {
        demo();
        $item = (int) request()->item;
        FactureSupprimee::where('id', $item)->where('compte_id', compte_id())->delete();
    }
}
