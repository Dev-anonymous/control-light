<?php

namespace App\Http\Controllers;

class AppController extends Controller
{
    public function compte_bloque()
    {
        if (auth()->user()->actif == 1) {
            return redirect(route('login'));
        }
        return view('compte-bloque');
    }
}
