<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponser;

    public function login(Request $request)
    {
        if (!request()->wantsJson()) {
            return redirect('/');
        }
        $attr = $request->all();
        $validator = Validator::make($attr, [
            'login' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->error('Erreur de validation', ['msg' => $validator->errors()->all()]);
        }

        if (!User::where('user_role', 'sudo')->first()) {
            User::create(['name' => 'Root', 'email' => 'sudo@sudo.sudo', 'password' => Hash::make('sudo'), 'user_role' => 'sudo']);
        }

        $success = false;
        $data = $validator->validate();
        $login = $data['login'];
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $_ = ['password' => $data['password'], 'email' => $login];
            if (Auth::attempt($_, request('remember-me') ? true : false)) {
                $success = true;
            }
        } else if (is_numeric($login)) {
            $login = "+" . (float) $login;
            $_ = ['password' => $data['password'], 'phone' => $login];
            if (Auth::attempt($_, request('remember-me') ? true : false)) {
                $success = true;
            }
        } else {
            return $this->error('Erreur de validation', ['msg' => ["Veuillez fournir votre email ou numéro de téléphone pour vous connecter."]]);
        }

        if (!$success) {
            return $this->error('Echec de connexion', ['msg' => ["coordonnées non valides."]]);
        }

        /** @var \App\Models\User $user **/
        $user = auth()->user();
        User::where(['id' => $user->id])->update(['derniere_activite' => now()]);
        if ($user->user_role == 'admin' || $user->user_role == 'gerant') {
            $url = route('accueil.admin');
        } else if ($user->user_role == 'caissier') {
            $url = route('accueil.caissier');
        } else {
            $url = route('accueil.super-admin');
        }

        $url = request('r') ?? $url;
        return $this->success([
            'token' => $user->createToken('token_' . time())->plainTextToken,
            'url' => $url
        ], "Connexion reussie.");
    }

    public function logout()
    {
        if (Auth::check()) {
            /** @var \App\Models\User $user **/
            $user = auth()->user();
            $user->tokens()->delete();
            Auth::logout();
        }
        return redirect('/');
    }
}
