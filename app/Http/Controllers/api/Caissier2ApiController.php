<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Caissier2ApiController extends Controller
{
    use ApiResponser;

    public function update_pass()
    {
        $user = auth()->user();
        $validator = Validator::make(request()->all(), [
            'password' => 'required|string',
            'npassword' => 'required|string|min:3|same:cpassword',
            'cpassword' => 'required|string|min:3|',
        ], ['npassword.same' => "Les deux mot de passe sont différents."]);
        if ($validator->fails()) {
            return $this->error('Erreur de validation', ['msg' => $validator->errors()->all()], 400);
        }

        $cp = request()->password;
        $np = request()->npassword;

        if (!(Hash::check($cp, $user->password))) {
            return $this->error('Erreur de validation', ['msg' => ['Le mot de passe actuel que vous avez saisi est incorrect.']], 400);
        }

        User::where('id', $user->id)->update(['password' => Hash::make($np)]);
        return $this->success(null, "Votre mot de passe a été modifié.");
    }

    public function update()
    {
        /** @var \App\Models\User $caissier **/
        $caissier = auth()->user();
        $validator = Validator::make(
            request()->all(),
            [
                'name' => 'required|string|max:45',
                'email' => 'sometimes|email|max:255|unique:users,email,' . $caissier->id,
                'phone' => 'sometimes|min:10|numeric|regex:/(\+)[0-9]{10}/|unique:users,phone,' . $caissier->id,
            ]
        );

        if ($validator->fails()) {
            return $this->error('Erreur de validation', ['msg' => $validator->errors()->all()], 400);
        }

        $em = request('email');
        $ph = request('phone');
        if (empty($em) and empty($ph)) {
            return $this->error('Erreur de création', ['msg' => ["Vous devez spécifier soit l'email, soit le numéro de téléphone."]], 400);
        }

        $data = $validator->validated();
        $caissier->update($data);
        return $this->success($data, 'Données mises à jour.');
    }
}
