<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConfigApiController extends Controller
{
    public function update()
    {
        demo();
        $action = request('action');
        $success = false;
        $message = 'Erreur';

        if ('devise' == $action) {
            $devise_auto = request()->devise_auto;
            if ($devise_auto  === "1" or  $devise_auto === "0") {
                setConfig('devise_auto', $devise_auto);
                $success = true;
                $message = "Votre configuration a été sauvegardée.";
            }
        }
        if ('facture' == $action) {
            $id = request('id');
            setConfig('facture_zero', $id);
            $success = true;
            $message = "Votre configuration a été sauvegardée.";
        }

        if ('prefixe' == $action) {
            setConfig('prefixe_facture', request('prefixe_facture'));
            setConfig('prefixe_bs', request('prefixe_bs'));
            setConfig('prefixe_be', request('prefixe_be'));
            $success = true;
            $message = "Votre configuration a été sauvegardée.";
        }

        return response()->json([
            'success' => $success,
            'message' => $message
        ]);
    }
}
