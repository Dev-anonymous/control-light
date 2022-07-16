<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\CategorieArticle;
use App\Models\Compte;
use App\Models\Devise;
use App\Models\GroupeArticle;
use App\Models\Shop;
use App\Models\Taux;
use App\Models\UniteMesure;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SuperAdmin extends Controller
{
    use ApiResponser;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->user_role != 'sudo') {
                abort(503);
            }
            return $next($request);
        });
    }
    public function index()
    {
        return view('sudo.index');
    }

    public function adduser()
    {
        $validator = Validator::make(request()->all(), [
            'client' => 'required|max:45',
            'magasin' => 'required|max:45',
            'password' => 'required|',
            'type' => 'required|in:online,local',
            'email' => 'sometimes|email|max:255|unique:users',
            'phone' => 'sometimes|min:10|numeric|regex:/(\+)[0-9]/|unique:users',
        ]);
        if ($validator->fails()) {
            return $this->error('Erreur de validation', ['msg' => $validator->errors()->all()], 200);
        }
        $data = $validator->validate();

        $em = request()->email;
        $pho = request()->phone;

        if (empty($em) and empty($pho)) {
            return $this->error('Erreur de validation', ['msg' => ["Veuillez fournir un email ou un numero de telephone"]], 200);
        }

        DB::transaction(function () use ($data) {
            $now = date('Y-m-d H:i:s');
            $cmpt['client'] = $data['client'];
            $cmpt['email'] = @$data['email'];
            $cmpt['phone'] = @$data['phone'];
            $cmpt['magasin'] = $data['magasin'];
            $cmpt['date_creation'] = $now;
            $cmpt['type'] = $data['type'];

            $cm = Compte::create($cmpt);
            $token = md5($cm->id * rand(2, 10000));
            $cm->update(['token' => $token]);

            $u['compte_id'] = $cm->id;
            $u['name'] = $data['client'];
            $u['email'] = @$data['email'];
            $u['phone'] = @$data['phone'];
            $u['user_role'] = 'admin';
            $u['password'] = Hash::make($data['password']);
            User::create($u);

            if (!Devise::all()->first()) {
                Devise::create(['devise' => "CDF"]);
                Devise::create(['devise' => "USD"]);
            }

            $cdf = Devise::where(['devise' => 'CDF'])->first();
            $usd = Devise::where(['devise' => 'USD'])->first();

            Taux::create(['devise_id' => $cdf->id, 'devise2_id' => $usd->id, 'taux' => 0.0005, 'compte_id' => $cm->id]);
            Taux::create(['devise_id' => $usd->id, 'devise2_id' => $cdf->id, 'taux' => 2000, 'compte_id' => $cm->id]);

            foreach ([
                'Alimentation' => ['Epices', 'Legumes', 'Aliment', 'Autre'],
                'Habillement' => ['Chaussure Dame', 'Chaussure homme', 'Lingerie', 'Divers'],
                'Quicaillerie' => ['Composant Electronique', 'Ordinateur'],
                'Divers' => ['Autres']
            ] as $gr => $cat) {
                $g =  GroupeArticle::create(['groupe' => $gr, 'compte_id' => $cm->id]);
                foreach ($cat as $el) {
                    CategorieArticle::create([
                        'categorie' => $el,
                        'groupe_article_id' => $g->id,
                        'compte_id' => $cm->id
                    ]);
                }
            }

            Shop::create([
                'shop' => 'Ma Boutique',
                'date_creation' => $now,
                'compte_id' => $cm->id
            ]);

            foreach (['Pièce', 'Mètre', 'Carton', 'Boite'] as $el) {
                UniteMesure::create(['unite_mesure' => $el, 'compte_id' => $cm->id]);
            }
        });

        return $this->success([
            'data' => $data
        ], "Compte créé.");
    }
}
