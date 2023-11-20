<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConfigApiController extends Controller
{
    public function update()
    {
        demo();
        $devise_auto = request()->devise_auto;
        $success = false;
        $message = 'Erreur';

        if ($devise_auto  === "1" or  $devise_auto === "0") {
            setConfig('devise_auto', $devise_auto);
            $success = true;
            $message = "Votre configuration a été sauvegardée.";
        }

        return response()->json([
            'success' => $success,
            'message' => $message
        ]);
    }
}
