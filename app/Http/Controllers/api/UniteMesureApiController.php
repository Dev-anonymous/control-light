<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\UniteMesure;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UniteMesureApiController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $unite = UniteMesure::where(['compte_id' => compte_id()])->get();
        return $this->success($unite);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'unite' => "required"
        ]);
        if ($validator->fails()) {
            return $this->error('Erreur de validation', ['msg' => $validator->errors()->all()]);
        }
        $data = $validator->validated()['unite'];
        $data = explode(',', $data);

        $exist = '';
        $ok = false;
        foreach ($data as $d) {
            $d = trim($d);
            if (!empty($d)) {
                if (UniteMesure::where(['compte_id' => compte_id(), 'unite_mesure' => $d])->first()) {
                    $exist .= "$d, ";
                } else {
                    UniteMesure::create(['unite_mesure' => $d, 'compte_id' => compte_id()]);
                    $ok = true;
                }
            }
        }

        $exist = substr($exist, 0, -2);

        if ($ok) {
            if (count($data) > 1) {
                $m = "Unités de mesure ajoutées avec succès.";
            } else {
                $m = "Unité de mesure ajouté avec succès.";
            }
            if (strlen($exist) > 0) {
                $m .= " Ces unités existent déjà : $exist";
            }
            return $this->success($data, $m);
        } else {
            if (empty($exist)) {
                $m = "Aucune donnée n'a été ajouté";
            } else {
                $m = " Ces unités existent déjà : $exist";
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
    public function update(UniteMesure $unite_mesure)
    {
        if ($unite_mesure->compte_id !=  compte_id()) {
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
            DB::statement("update unite_mesure set par_defaut=0 where compte_id=$cid");
            $unite_mesure->update(['par_defaut' => $to]);
            if ($to == 1) {
                $m = "L'unité \"$unite_mesure->unite_mesure\" est maintenant marquée comme unité par defaut";
            } else {
                $m = "L'unité \"$unite_mesure->unite_mesure\" n'est plus l'unité par defaut";
            }
            return $this->success([], $m);
        } else {
            $validator = Validator::make(request()->all(), [
                'unite_mesure' => "required|unique:unite_mesure,unite_mesure,{$unite_mesure->id}"
            ]);
            if ($validator->fails()) {
                return $this->error('Erreur de validation', ['msg' => $validator->errors()->all()]);
            }
            $data = $validator->validated();
            $unite_mesure->update($data);
            return $this->success($data, 'Données mises à jour.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(UniteMesure $unite_mesure)
    {
        if ($unite_mesure->compte_id != compte_id()) {
            abort(403);
        }
        $n = $unite_mesure->articles()->count();
        if ($n > 0) {
            return $this->error("Cette unité de mesure contient $n article(s), Vous devez d'abord supprimer tous les articles avec cette unité.");
        } else {
            $unite_mesure->delete();
            return $this->success(null, "L'unité de mesure \"$unite_mesure->unite_mesure\" a été supprimée.");
        }
    }
}
