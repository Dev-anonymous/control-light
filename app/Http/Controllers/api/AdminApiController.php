<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminApiController extends Controller
{
    use ApiResponser;

    public function update(Request $request)
    {
        demo();
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:45',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|min:10|numeric|regex:/(\+)[0-9]{10}/|unique:users,phone,' . $user->id,
        ]);
        if ($validator->fails()) {
            return $this->error('Erreur de validation', ['msg' => $validator->errors()->all()], 400);
        }
        $data = $validator->validate();

        User::where('id', $user->id)->update($data);

        return $this->success([
            'data' => $data
        ], "Vos données ont été mises à jour.");
    }

    public function update_pass()
    {
        demo();
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

    public function shop(Request $request)
    {
        demo();
        $validator = Validator::make($request->all(), [
            'shop' => 'required|string|max:128',
            'adresse' => 'required|string|max:128',
            'contact' => 'required|string|max:128',
            'rccm' => 'sometimes|string|max:128',
            'idnat' => 'sometimes|string|max:128',
        ]);
        if ($validator->fails()) {
            return $this->error('Erreur de validation', ['msg' => $validator->errors()->all()], 400);
        }
        $data = $validator->validate();

        $shop  = Shop::where(['compte_id' => compte_id()])->first();
        if ($shop) {
            $shop->update($data);
        } else {
            Shop::create($data);
        }
        return $this->success([
            'data' => $data
        ], "Vos données ont été mises à jour.");
    }
}
