<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use stdClass;

class CaissierApiController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::where(['user_role' => 'caissier', 'compte_id' => compte_id()])->orderBy('id', 'desc')->get(['id', 'name', 'email', 'phone', 'derniere_activite', 'actif']);
        $tab = [];
        foreach ($user as $e) {
            $a = (object) $e->toArray();
            $a->derniere_activite = empty($e->derniere_activite) ? '-' : $e->derniere_activite->format('Y-m-d H:i:s');
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
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:45',
                'email' => 'sometimes|email|max:255|unique:users',
                'phone' => 'sometimes|min:10|numeric|regex:/(\+)[0-9]{10}/|unique:users,phone',
                'password' => 'required|',
            ]
        );

        if ($validator->fails()) {
            return $this->error('Erreur de validation', ['msg' => $validator->errors()->all()]);
        }

        $em = request('email');
        $ph = request('phone');
        if (empty($em) and empty($ph)) {
            return $this->error('Erreur de création', ['msg' => ["Vous devez spécifier soit l'email, soit le numéro de téléphone pour créer un compte."]]);
        }

        $data = $validator->validate();
        $data['password'] = Hash::make($data['password']);
        $data['user_role'] = 'caissier';
        $data['compte_id'] = compte_id();
        $user = User::create($data);

        return $this->success($user, "Compte créé avec succès.");
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
    public function update(User $caissier)
    {
        if (auth()->user()->user_role != 'admin') {
            abort(401);
        }
        if ($caissier->compte_id != compte_id()) {
            abort(403);
        }
        $default = request()->default;
        $action = request()->action;

        if ($action == 'reset') {
            $caissier->update(['password' => Hash::make('123456')]);
            return $this->success([], 'Mot de passe réinitialisé => 123456');
        }

        if ($default) {
            $validator = Validator::make(request()->all(), [
                'to' => "required|in:1,0"
            ]);
            if ($validator->fails()) {
                return $this->error('Erreur de validation', ['msg' => $validator->errors()->all()]);
            }
            $to = (int) request()->to;
            $caissier->update(['actif' => $to]);
            if ($to == 1) {
                $m = "Le compte débloqué";
            } else {
                $m = "Le compte bloqué";
            }
            return $this->success([], $m);
        } else {
            $validator = Validator::make(
                request()->all(),
                [
                    'name' => 'required|string|max:45',
                    'email' => 'sometimes|email|max:255|unique:users,email,' . $caissier->id,
                    'phone' => 'sometimes|min:10|numeric|regex:/(\+)[0-9]{10}/|unique:users,phone,' . $caissier->id,
                ]
            );

            if ($validator->fails()) {
                return $this->error('Erreur de validation', ['msg' => $validator->errors()->all()]);
            }

            $em = request('email');
            $ph = request('phone');
            if (empty($em) and empty($ph)) {
                return $this->error('Erreur de création', ['msg' => ["Vous devez spécifier soit l'email, soit le numéro de téléphone."]]);
            }

            $data = $validator->validated();
            $caissier->update($data);
            return $this->success($data, 'Données mises à jour.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $caissier)
    {
        if (auth()->user()->user_role != 'admin') {
            abort(401);
        }
        if ($caissier->compte_id != compte_id()) {
            abort(403);
        }
        if ($caissier->user_role == 'caissier') {
            $caissier->delete();
            return $this->success(null, "Le compte du caissier \"$caissier->name\" a été supprimé.");
        }
    }
}
