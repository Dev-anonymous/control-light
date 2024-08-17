<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\app\AdminController;
use App\Http\Controllers\app\CaissierController;
use App\Http\Controllers\app\SuperAdmin;
use App\Http\Controllers\AppController;
use App\Http\Controllers\CommonController;
use App\Models\Article;
use App\Models\Vente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('test', function () {
    return view('test');
});

Route::get('/offline', function () {
    return view("offline");
});
Route::get('', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->user_role == 'admin') {
            $url = route('accueil.admin');
        } else if ($user->user_role == 'caissier') {
            $url = route('accueil.caissier');
        } else if ($user->user_role == 'gerant') {
            $url = route('accueil.admin');
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

            Route::get('/caissier/ventes-magasin', [CaissierController::class, 'ventesMagasin'])->name('ventes-magasin.caissier');
            Route::get('/caissier/ventes', [CaissierController::class, 'ventes'])->name('ventes.caissier');

            Route::get('/caissier/articles', [CaissierController::class, 'articles'])->name('articles.caissier');

            Route::get('/caissier/cassier', [CaissierController::class, 'cassier'])->name('cassier.caissier');

            Route::get('/caissier/compte', [CaissierController::class, 'compte'])->name('compte.caissier');
        });

        Route::middleware('admin.mdlw')->group(function () {
            Route::get('/admin', [AdminController::class, 'index'])->name('accueil.admin');

            Route::get('/admin/etat-magasin', [AdminController::class, 'etatMagasin'])->name('etat-magasin.admin');

            Route::get('/admin/ventes/ventes-magasin', [AdminController::class, 'ventesMagasin'])->name('ventes-magasin.admin');
            Route::get('/admin/ventes/ventes', [AdminController::class, 'ventes'])->name('ventes.admin');

            Route::get('/admin/articles/code', [AdminController::class, 'scan_article'])->name('scan-article.admin');
            Route::get('/admin/articles/code-barre', [AdminController::class, 'code_barre'])->name('code-barre.admin');
            Route::get('/admin/articles/articles', [AdminController::class, 'articles'])->name('articles.admin');
            Route::get('/admin/articles/details/{article}', [AdminController::class, 'article_detail'])->name('detail-article.admin');

            Route::get('/admin/utilisateurs', [AdminController::class, 'cassier'])->name('cassier.admin');
            Route::get('/admin/clients', [AdminController::class, 'clients'])->name('clients.admin');

            Route::get('/admin/params/groupe-articles', [AdminController::class, 'groupe_article'])->name('groupe-article.admin');
            Route::get('/admin/params/categorie-articles', [AdminController::class, 'categorie_article'])->name('categore-article.admin');
            Route::get('/admin/params/unite-mesure', [AdminController::class, 'unite_mesure'])->name('unite-mesure.admin');
            Route::get('/admin/params/devise', [AdminController::class, 'devise'])->name('devise.admin');
            Route::get('/admin/params/compte', [AdminController::class, 'compte'])->name('compte.admin');

            Route::get('/admin/factures-supprimees', [AdminController::class, 'facture_sup'])->name('facture-supprimee.admin');
        });

        Route::get('/common/proforma', [CommonController::class, 'proforma'])->name('proforma');
        Route::get('/common/proforma/details/{proforma}', [CommonController::class, 'proforma_show'])->name('proforma.show');
        Route::get('/common/proforma/modele-proforma', [CommonController::class, 'modele_proforma'])->name('proforma.modele');
        Route::get('/common/proforma/facture-proforma/{id}', [CommonController::class, 'facture_proforma'])->name('proforma.facture');
        Route::get('/preview-proforma/{id}', [CommonController::class, 'preview_proforma'])->name('proforma.preview');
        Route::get('/preview-proforma-html/{proforma}', [CommonController::class, 'preview_proforma_html'])->name('proforma.preview_html');
        Route::get('/common/default', [CommonController::class, 'proforma_default'])->name('proforma_default');

        Route::get('/common/bon-dentree', [CommonController::class, 'bonentree'])->name('bonentree.common');
        Route::get('/common/bon-desortie', [CommonController::class, 'bonsortie'])->name('bonsortie.common');
    });
    Route::get('/super-admin', [SuperAdmin::class, 'index'])->name('accueil.super-admin');
});

// Route::get('fac', function () {
//     $fa = \App\Models\Facture::where(['compte_id' => compte_id(), 'numero_facture' => null])->get();
//     \Artisan::call('taux:update');

//     foreach ($fa as $k => $el) {
//         $num = $k + 1;
//         if ($num <= 9) {
//             $num = "000$num";
//         } else if ($num >= 10 and $num <= 99) {
//             $num = "00$num";
//         } else if ($num >= 100 and $num <= 999) {
//             $num = "0$num";
//         }
//         $el->update(['numero_facture' => $num]);
//     }
// });

Route::get('mig', function () {
    foreach (Article::whereNull('prix_achat')->get() as $el) {
        $el->update(['prix_achat' => $el->prix, 'devise_achat' => $el->devise->devise]);
    }
    foreach (Vente::whereNull('marge_cdf')->get() as $el) {
        $el->update(['marge_cdf' => 0, 'marge_usd' => 0]);
    }
});

Route::get('/applink', function () {
    $a = $targetFolder = base_path() . '/storage/app/public';
    $b = $linkFolder = $_SERVER['DOCUMENT_ROOT'] . '/storage';
    $c = symlink($targetFolder, $linkFolder);
    dd($a, $b, $c);
});
