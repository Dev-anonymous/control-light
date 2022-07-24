<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\CategorieArticle;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use stdClass;

class CategorieArticleApiController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorie = CategorieArticle::orderBy('id', 'desc')->where('compte_id', compte_id());
        $groupe = request()->groupe;
        if ($groupe) {
            $categorie = $categorie->where('groupe_article_id', $groupe);
        }
        $tab = [];
        foreach ($categorie->get() as $e) {
            $a = new stdClass();
            $a->id = $e->id;
            $a->categorie = $e->categorie;
            $a->groupe = $e->groupe_article->groupe;
            $a->par_defaut = $e->par_defaut;
            array_push($tab, $a);
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
        if (auth()->user()->user_role != 'admin') {
            abort(401);
        }
        $validator = Validator::make(request()->all(), [
            'categorie' => "required",
            'groupe_article_id' => "required|exists:groupe_article,id",
        ]);
        if ($validator->fails()) {
            return $this->error('Erreur de validation', ['msg' => $validator->errors()->all()]);
        }
        $data = $validator->validated()['categorie'];
        $gr = $validator->validated()['groupe_article_id'];
        $data = explode(',', $data);

        $exist = '';
        $ok = false;
        foreach ($data as $d) {
            $d = trim($d);
            if (!empty($d)) {
                if (CategorieArticle::where(['categorie' => $d, 'groupe_article_id' => $gr, 'compte_id' => compte_id()])->first()) {
                    $exist .= "$d, ";
                } else {
                    CategorieArticle::create(['categorie' => $d, 'groupe_article_id' => $gr, 'compte_id' => compte_id()]);
                    $ok = true;
                }
            }
        }

        $exist = substr($exist, 0, -2);

        if ($ok) {
            if (count($data) > 1) {
                $m = "Catégories ajoutées avec succès.";
            } else {
                $m = "Catégorie ajoutée avec succès.";
            }
            if (strlen($exist) > 0) {
                $m .= " Ces catégories existent déjà : $exist";
            }
            return $this->success($data, $m);
        } else {
            if (empty($exist)) {
                $m = "Aucune donnée n'a été ajoutée";
            } else {
                $m = " Ces catégories existent déjà : $exist";
            }
            return $this->error($m);
        }
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
    public function update(CategorieArticle $categorie_article)
    {
        if (auth()->user()->user_role != 'admin') {
            abort(401);
        }
        if ($categorie_article->compte_id != compte_id()) {
            abort(403);
        }
        $default = request()->default;
        if ($default) {
            $validator = Validator::make(request()->all(), [
                'to' => "required|in:1,0"
            ]);
            if ($validator->fails()) {
                return $this->error('Erreur de validation', ['msg' => $validator->errors()->all()]);
            }
            $to = (int) request()->to;
            $cid = compte_id();
            DB::statement("update categorie_article set par_defaut=0 where compte_id=$cid and groupe_article_id=$categorie_article->groupe_article_id");
            $categorie_article->update(['par_defaut' => $to]);
            if ($to == 1) {
                $m = "La catégorie \"$categorie_article->categorie\" est maintenant marquée comme catégorie par defaut";
            } else {
                $m = "La catégorie \"$categorie_article->categorie\" n'est plus la catégorie par defaut";
            }
            return $this->success([], $m);
        } else {
            $validator = Validator::make(request()->all(), [
                'categorie' => "required|unique:categorie_article,categorie,{$categorie_article->id}"
            ]);
            if ($validator->fails()) {
                return $this->error('Erreur de validation', ['msg' => $validator->errors()->all()]);
            }
            $data = $validator->validated();
            $categorie_article->update($data);
            return $this->success($data, 'Données mises à jour.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategorieArticle $categorie_article)
    {
        if (auth()->user()->user_role != 'admin') {
            abort(401);
        }
        if ($categorie_article->compte_id != compte_id()) {
            abort(403);
        }
        $n = $categorie_article->articles()->count();
        if ($n > 0) {
            return $this->error("Cette catégorie contient $n article(s), Vous devez d'abord supprimer tous les articles de cette catégorie.");
        } else {
            $categorie_article->delete();
            return $this->success(null, "La catégorie \"$categorie_article->categorie\" a été supprimée.");
        }
    }
}
