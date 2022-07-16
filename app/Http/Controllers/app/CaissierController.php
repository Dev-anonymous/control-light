<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CaissierController extends Controller
{
    public function index()
    {
        return view('caissier.index');
    }

    public function articles()
    {
        return view('caissier.articles');
    }

    public function compte()
    {
        return view('caissier.compte');
    }

    public function ventesMagasin()
    {
        return view('caissier.ventes-magasin');
    }

    public function ventes()
    {
        return view('caissier.ventes');
    }

    public function cassier()
    {
        return view('caissier.cassier');
    }
}
