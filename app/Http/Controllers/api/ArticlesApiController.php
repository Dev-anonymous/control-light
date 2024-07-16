<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Approvisionnement;
use App\Models\Article;
use App\Models\Devise;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use stdClass;

class ArticlesApiController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::orderBy('article')->where('compte_id', compte_id());
        $categorie = request()->categorie;
        $filtre = request()->filtre;
        $filtre2 = request()->filtre2;
        if ($categorie) {
            $articles = $articles->where('categorie_article_id', $categorie);
        }

        if ($filtre) {
            $max = date('Y-m-d', strtotime('+30 days'));
            $articles = $articles->where(function ($query) use ($max) {
                $query->whereNotNull('date_expiration')->whereDate('date_expiration', '>=', $max);
                $query->orWhereNull('date_expiration');
            });
        }

        if ($filtre2 == 'reduction') {
            $articles = $articles->where('reduction', '>', 0);
        }
        if ($filtre2 == 'no-reduction') {
            $articles = $articles->where('reduction', '=', 0);
        }
        if ($filtre2 == 'no-expire-date') {
            $articles = $articles->WhereNull('date_expiration');
        }
        if ($filtre2 == 'expired') {
            $now = date('Y-m-d');
            $articles = $articles->where(function ($query) use ($now) {
                $query->whereNotNull('date_expiration')->whereDate('date_expiration', '<', $now);
            });
        }
        if ($filtre2 == 'expire-in30') {
            $now = date('Y-m-d');
            $max = date('Y-m-d', strtotime('+30 days'));
            $articles = $articles->where(function ($query) use ($max, $now) {
                $query->whereNotNull('date_expiration')->whereDate('date_expiration', '>=', $now);
                $query->whereNotNull('date_expiration')->whereDate('date_expiration', '<=', $max);
            });
        }
        if ($filtre2 == 'expire-in60') {
            $now = date('Y-m-d');
            $max = date('Y-m-d', strtotime('+60 days'));
            $articles = $articles->where(function ($query) use ($max, $now) {
                $query->whereNotNull('date_expiration')->whereDate('date_expiration', '>=', $now);
                $query->whereNotNull('date_expiration')->whereDate('date_expiration', '<=', $max);
            });
        }
        if ($filtre2 == 'stock-20') {
            $articles = $articles->where('stock', '>', 0)->where('stock', '<=', 20);
        }
        if ($filtre2 == 'stock-50') {
            $articles = $articles->where('stock', '>', 0)->where('stock', '<=', 50);
        }
        if ($filtre2 == 'stock-0') {
            $articles = $articles->where('stock', '=', 0);
        }

        $filtermarge = in_array($filtre2, ['solde', 'gain', 'perte']);

        $tab = [];
        foreach ($articles->get() as $e) {
            $a = new stdClass();
            $a->id = $e->id;
            $a->article = $e->article;
            $a->categorie = $e->categorie_article->categorie;
            $a->groupe = $e->categorie_article->groupe_article->groupe;
            $a->prix = montant($e->prix, $e->devise->devise);
            $a->prix_achat = montant($e->prix_achat, $e->devise_achat);
            $a->reduction = $red = (float) $e->reduction;
            $a->prix_min = $red > 0 ? montant(reduction($e->prix,  $red), $e->devise->devise) : $a->prix;
            $a->unite_mesure = $e->unite_mesure->unite_mesure;
            $a->stock = $e->stock;
            $a->code = $e->code;

            $a->marge = marge($e);

            if (empty($e->date_expiration)) {
                $a->date_expiration =  '-';
                $a->can_expire =  false;
                $a->jour_restant = '-';
            } else {
                $a->date_expiration =  $e->date_expiration->format('Y-m-d');
                $a->can_expire =  true;

                $fdate = date('Y-m-d');
                $tdate = $a->date_expiration;
                $datetime1 = strtotime($fdate);
                $datetime2 = strtotime($tdate);
                $days = (int)(($datetime2 - $datetime1) / 86400);
                $days = $days >= 0 ? ++$days : $days;
                $a->jour_restant = $days;
            }
            if ($filtermarge) {
                if ($filtre2 == $a->marge->result) {
                    array_push($tab, $a);
                }
            } else {
                array_push($tab, $a);
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
        demo();
        if (auth()->user()->user_role != 'admin') {
            abort(401);
        }
        $validator = Validator::make(
            $request->all(),
            [
                'article' => 'required|string|max:128',
                'categorie_article_id' => 'required|exists:categorie_article,id',
                'unite_mesure_id' => 'required|exists:unite_mesure,id',
                'devise_id' => 'required|exists:devise,id',
                'prix' => 'required|numeric|min:0.1',
                'prix_achat' => 'required|numeric|min:0.1',
                'devise_achat' => 'required|string|in:CDF,USD',
                'stock' => 'required|numeric|integer|min:1',
                'reduction' => 'required|numeric|min:0|max:90',
            ]
        );

        if ($validator->fails()) {
            return $this->error('Erreur de validation', ['msg' => $validator->errors()->all()]);
        }

        if (Article::where(['article' => $request->article, 'categorie_article_id' => $request->categorie_article_id, 'compte_id' => compte_id()])->first()) {
            return $this->error('Erreur', ['msg' => ["Cet article existe déjà dans cette catégorie."]]);
        }

        $data = $validator->validate();
        $data['code'] = code_article();
        if (request()->can_expire) {
            $validator = Validator::make(
                $request->all(),
                [
                    'date_expiration' => 'required|after:' . date('Y-m-d')
                ]
            );

            if ($validator->fails()) {
                return $this->error('Erreur de validation', ['msg' => $validator->errors()->all()]);
            }

            $data['date_expiration'] = request()->date_expiration;
        }
        DB::transaction(function () use ($data) {
            $data['compte_id'] = compte_id();
            $art = Article::create($data);
            Approvisionnement::create(['article_id' => $art->id, 'qte' => $data['stock'], 'date' => now('Africa/Lubumbashi'), 'compte_id' => compte_id()]);
        });
        return $this->success([], "Article ajouté avec succès.");
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
    public function update(Article $article)
    {
        demo();
        if (auth()->user()->user_role != 'admin') {
            abort(401);
        }
        if ($article->compte_id != compte_id()) {
            abort(403);
        }
        $action = request()->action;

        if ($action == 'appro') {
            $validator = Validator::make(request()->all(), ['stock' => 'required|numeric|integer|min:1']);

            if ($validator->fails()) {
                return $this->error('Erreur de validation', ['msg' => $validator->errors()->all()]);
            }

            $stock = request()->stock;
            $date_expiration = request()->date_expiration;
            $update_date = request()->update_date;
            if ($update_date) {
                $validator = Validator::make(request()->all(), ['date_expiration' => 'required|after:' . date('Y-m-d')]);
                if ($validator->fails()) {
                    return $this->error('Erreur de validation', ['msg' => $validator->errors()->all()]);
                }
                $data['date_expiration'] = $date_expiration;
            } else {
                $data['date_expiration'] = $article->date_expiration;
            }

            $st = (int) $article->stock;
            $st += $stock;

            $data['article'] = $article;
            $data['st'] = $st;
            $data['qte'] = $stock;

            DB::transaction(function () use ($data) {
                $article = $data['article'];
                $date_expiration = $data['date_expiration'];
                $st = $data['st'];
                $qte = $data['qte'];

                Approvisionnement::create(['qte' => $qte, 'article_id' => $article->id, 'compte_id' => compte_id()]);
                $article->update(['stock' => $st, 'date_expiration' => $date_expiration]);
            });
            return $this->success([], "Le stock de l'article été mis à jour.");
        } else {
            $validator = Validator::make(request()->all(), [
                'article' => 'required|string|max:128',
                'categorie_article_id' => 'required|exists:categorie_article,id',
                'devise_id' => 'required|exists:devise,id',
                'prix' => 'required|numeric|min:0.1',
                'prix_achat' => 'required|numeric|min:0.1',
                'devise_achat' => 'required|string|in:CDF,USD',
                'reduction' => 'required|numeric|min:0|max:90',
                'date_expiration' => 'sometimes|after:' . date('Y-m-d'),
            ]);
            if ($validator->fails()) {
                return $this->error('Erreur de validation', ['msg' => $validator->errors()->all()]);
            }

            $article->update($validator->validated());
            return $this->success([], "L'article a été mis à jour.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        demo();
        if (auth()->user()->user_role != 'admin') {
            abort(401);
        }
        if ($article->compte_id != compte_id()) {
            abort(403);
        }
        $article->delete();
        return $this->success([], "L'article a été supprimé.");
    }

    public function import(Request $request)
    {
        demo();
        if (auth()->user()->user_role != 'admin') {
            abort(401);
        }
        $validator = Validator::make(
            $request->all(),
            [
                'categorie_article_id' => 'required|exists:categorie_article,id',
                'unite_mesure_id' => 'required|exists:unite_mesure,id',
                'file' => 'required|mimes:xls,xlsx',
            ]
        );

        if ($validator->fails()) {
            return $this->error('Erreur de validation', ['msg' => $validator->errors()->all()]);
        }

        $temp_dir = $_FILES['file']['tmp_name'];
        $ext  = pathinfo($_FILES['file']['name'])['extension'];
        $ext = strtolower($ext);

        $reader = new Xlsx();
        $spreadsheet = $reader->load($temp_dir);
        $sheet_data  = $spreadsheet->getActiveSheet(0)->toArray();

        $ni = $j = 0;
        $err = [];

        for ($i = 1; $i < count($sheet_data); $i++) {
            $article = $sheet_data[$i]['0'];
            $qte = $sheet_data[$i]['1'];
            $prix = $sheet_data[$i]['2'];
            $reduction = (float) $sheet_data[$i]['3'];
            $devise = $sheet_data[$i]['4'];
            $exp = trim($sheet_data[$i]['5']);

            if (empty($article)) {
                $err[] = "Ligne " . ($i + 1) . " : nom de l'article vide.";
                $ni++;
                continue;
            }

            if (!(is_numeric($qte) and $qte > 0)) {
                $err[] = "Ligne " . ($i + 1) . " : qantité invalide => \"$qte\".";
                $ni++;
                continue;
            }
            if (!(is_numeric($prix) and $prix > 0)) {
                $err[] = "Ligne " . ($i + 1) . " : prix invalide => \"$prix\".";
                $ni++;
                continue;
            }
            if (!($reduction >= 0 and $reduction <= 90)) {
                $err[] = "Ligne " . ($i + 1) . " : reduction invalide => \"$reduction\". La valeur doit etre entre 0 et 90.";
                $ni++;
                continue;
            }
            if (!in_array($devise, ['CDF', 'USD'])) {
                $err[] = "Ligne " . ($i + 1) . " : devise invalide => \"$devise\".";
                $ni++;
                continue;
            }
            if (!empty($exp)) {
                $t = strtotime($exp);
                if (!$t) {
                    $err[] = "Ligne " . ($i + 1) . " : format date invalide => \"$exp\". La date doit etre au format AAAA/MM/JJ";
                    $ni++;
                    continue;
                }
            }
            if (Article::where(['article' => $article, 'categorie_article_id' => $request->categorie_article_id, 'compte_id' => compte_id()])->first()) {
                $err[] = "Ligne " . ($i + 1) . " : l'article \"$article\" existe déjà dans cette catégorie.";
                $ni++;
                continue;
            }

            $data = [
                'article' => $article,
                'categorie_article_id' => $request->categorie_article_id,
                'compte_id' => compte_id(),
                'unite_mesure_id' => $request->unite_mesure_id,
                'reduction' => $reduction,
                'prix' => $prix,
                'devise_id' => Devise::where('devise', $devise)->first()->id,
                'date_expiration' => empty($exp) ? null : $exp,
                'stock' => (int) $qte,
                'code' => code_article()
            ];

            DB::transaction(function () use ($data) {
                $art = Article::create($data);
                Approvisionnement::create(['article_id' => $art->id, 'qte' => $data['stock'], 'date' => now('Africa/Lubumbashi'), 'compte_id' => compte_id()]);
            });
            $j++;
        }

        $message = '';
        if ($j) {
            $message .= "$j ligne(s) importée(e) <br><br>";
        }
        if ($ni) {
            $message .= "$ni ligne(s) non importée(e) <br><br>";
            $message .= implode('<br>', $err);
        }

        if (empty($message)) {
            $message = 'Aucun article importé';
        }

        if ($j and $ni) {
            $cl = 'warning';
        } elseif ($j and !$ni) {
            $cl = 'success';
        } else {
            $cl = 'danger';
        }

        if ($ni == count($sheet_data) - 1) {
            $message .= '<br>Aucun article importé';
        }

        return response()->json([
            'success' => (bool) $j,
            'classe' => $cl,
            'message' => $message,
        ], 200);
    }
}
