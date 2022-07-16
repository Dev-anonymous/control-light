<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MobileDataApiController extends Controller
{
    use ApiResponser;
    public function checkdata()
    {
        $qr = request()->qr;
        if (!$qr) {
            return $this->error('QrCode non valide', ['msg' => []]);
        }

        $qr = base64_decode($qr);
        $user = User::where('id', $qr)->first();
        if (!$user) {
            return $this->error('QrCode non valide', ['msg' => []]);
        }
        return $this->success("", ucfirst($user->name) . "($user->user_role)");
    }

    public function qrlogin()
    {
        $validator = Validator::make(request()->all(), [
            'qr' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->error('Erreur de validation', ['msg' => $validator->errors()->all()]);
        }

        $qr = request()->qr;
        $password = request()->password;

        $qr = base64_decode($qr);
        $user = User::where('id', $qr)->first();
        if (!$user) {
            return $this->error('Données non valides');
        }

        $data['password'] = $password;
        if (!empty($user->phone)) {
            $data['phone'] = $user->phone;
        } else if (!empty($user->email)) {
            $data['email'] = $user->email;
        } else {
            return $this->error('Echec de connexion');
        }

        $data['password'] = $password;

        if (Auth::attempt($data)) {
            if ($user->actif == 0) {
                return $this->error('Compte bloqué.');
            }
            return $this->success($user, 'Connexion reussi.');
        } else {
            return $this->error('Echec de connexion');
        }
    }
}
