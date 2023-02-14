<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\app\AdminController;
use App\Http\Controllers\app\CaissierController;
use App\Http\Controllers\app\SuperAdmin;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->user_role == 'admin') {
            $url = route('accueil.admin');
        } else if ($user->user_role == 'caissier') {
            $url = route('accueil.caissier');
        } else {
            $url = route('accueil.super-admin');
        }
        return redirect($url);
    }
    return view('login');
})->name('login');

Route::post('/auth/login', [AuthController::class, 'login'])->name('login.web');
Route::get('/auth/logout', [AuthController::class, 'logout'])->name('logout.web');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/compte-bloque', [AppController::class, 'compte_bloque'])->name('compte-bloque.web');
    Route::middleware('compteBloque.mdlw')->group(function () {
        Route::middleware('caissier.mdlw')->group(function () {
            Route::get('/caissier', [CaissierController::class, 'index'])->name('accueil.caissier');
            Route::get('/caissier/articles', [CaissierController::class, 'articles'])->name('articles.caissier');
            Route::get('/caissier/ventes-magasin', [CaissierController::class, 'ventesMagasin'])->name('ventes-magasin.caissier');
            Route::get('/caissier/ventes', [CaissierController::class, 'ventes'])->name('ventes.caissier');
            Route::get('/caissier/compte', [CaissierController::class, 'compte'])->name('compte.caissier');
            Route::get('/caissier/cassier', [CaissierController::class, 'cassier'])->name('cassier.caissier');
        });

        Route::middleware('admin.mdlw')->group(function () {
            Route::get('/admin', [AdminController::class, 'index'])->name('accueil.admin');
            Route::get('/admin/compte', [AdminController::class, 'compte'])->name('compte.admin');

            Route::get('/admin/factures-supprimees', [AdminController::class, 'facture_sup'])->name('facture-supprimee.admin');

            Route::get('/admin/params/groupe-articles', [AdminController::class, 'groupe_article'])->name('groupe-article.admin');
            Route::get('/admin/params/categorie-articles', [AdminController::class, 'categorie_article'])->name('categore-article.admin');
            Route::get('/admin/params/unite-mesure', [AdminController::class, 'unite_mesure'])->name('unite-mesure.admin');

            Route::get('/admin/cassier', [AdminController::class, 'cassier'])->name('cassier.admin');

            Route::get('/admin/code-barre', [AdminController::class, 'code_barre'])->name('code-barre.admin');
            Route::get('/admin/articles', [AdminController::class, 'articles'])->name('articles.admin');
            Route::get('/admin/articles/details/{article}', [AdminController::class, 'article_detail'])->name('detail-article.admin');

            Route::get('/admin/ventes-magasin', [AdminController::class, 'ventesMagasin'])->name('ventes-magasin.admin');
            Route::get('/admin/ventes', [AdminController::class, 'ventes'])->name('ventes.admin');

            Route::get('/admin/etat-magasin', [AdminController::class, 'etatMagasin'])->name('etat-magasin.admin');

            Route::get('/admin/devise', [AdminController::class, 'devise'])->name('devise.admin');
        });
    });
    Route::get('/super-admin', [SuperAdmin::class, 'index'])->name('accueil.super-admin');
});
