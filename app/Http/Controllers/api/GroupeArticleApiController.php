<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\CategorieArticle;
use App\Models\GroupeArticle;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GroupeArticleApiController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groupe = GroupeArticle::where('compte_id', compte_id())->get();
        return $this->success($groupe);
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
        $validator = Validator::make(request()->all(), [
            'groupe' => "required"
        ]);
        if ($validator->fails()) {
            return $this->error('Erreur de validation', ['msg' => $validator->errors()->all()]);
        }
        $data = $validator->validated()['groupe'];
        $data = explode(',', $data);

        $exist = '';
        $ok = false;
        foreach ($data as $d) {
            $d = trim($d);
            if (!empty($d)) {
                if (GroupeArticle::where(['groupe' => $d, 'compte_id' => compte_id()])->first()) {
                    $exist .= "$d, ";
                } else {
                    GroupeArticle::create(['groupe' => $d, 'compte_id' => compte_id()]);
                    $ok = true;
                }
            }
        }

        $exist = substr($exist, 0, -2);

        if ($ok) {
            if (count($data) > 1) {
                $m = "Groupes ajoutés avec succès.";
            } else {
                $m = "Groupe ajouté avec succès.";
            }
            if (strlen($exist) > 0) {
                $m .= " Ces groupes existent déjà : $exist";
            }
            return $this->success($data, $m);
        } else {
            if (empty($exist)) {
                $m = "Aucune donnée n'a été ajouté";
            } else {
                $m = " Ces groupes existent déjà : $exist";
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
    public function update(GroupeArticle $groupe_article)
    {
        demo();
        if (auth()->user()->user_role != 'admin') {
            abort(401);
        }
        if ($groupe_article->compte_id != compte_id()) {
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
            DB::statement("update groupe_article set par_defaut=0 where compte_id=$cid");
            $groupe_article->update(['par_defaut' => $to]);
            if ($to == 1) {
                $m = "Le groupe \"$groupe_article->groupe\" est maintenant marqué comme groupe par defaut";
            } else {
                $m = "Le groupe \"$groupe_article->groupe\" n'est plus le groupe par defaut";
            }
            return $this->success([], $m);
        } else {
            $validator = Validator::make(request()->all(), [
                'groupe' => "required"
            ]);
            if ($validator->fails()) {
                return $this->error('Erreur de validation', ['msg' => $validator->errors()->all()]);
            }

            $t = GroupeArticle::where('groupe', '=', $groupe_article->groupe)->where('compte_id', '=', compte_id())->where('id', '<>', $groupe_article->id);
            if ($t->first()) {
                return $this->error('Erreur de validation', ['msg' => ["Ce groupe existe déjà."]]);
            }

            $data = $validator->validated();
            $groupe_article->update($data);
            return $this->success($data, 'Données mises à jour.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(GroupeArticle $groupe_article)
    {
        demo();
        if (auth()->user()->user_role != 'admin') {
            abort(401);
        }
        if ($groupe_article->compte_id != compte_id()) {
            abort(403);
        }
        $n = Article::whereIn('categorie_article_id', CategorieArticle::where('groupe_article_id', $groupe_article->id)->pluck('id')->all())->get()->count();
        if ($n > 0) {
            return $this->error("Ce groupe contient une ou plusieurs \"catégorie d'article\" qui contienent à leur tour un ou plusieurs articles. Pour supprimer ce groupe, allez dans la liste des vos articles et supprimer tous les articles qui appartiennent  à la catégorie ou aux catégorie de ce goupe; puis revenew sur cette page pour supprimer ce groupe.");
        } else {
            $groupe_article->delete();
            return $this->success(null, "Le groupe \"$groupe_article->groupe\" a été supprimé.");
        }
    }
}
