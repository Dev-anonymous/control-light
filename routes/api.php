<?php

use App\Http\Controllers\api\AdminApiController;
use App\Http\Controllers\api\ArticlesApiController;
use App\Http\Controllers\api\Caissier2ApiController;
use App\Http\Controllers\api\CaissierApiController;
use App\Http\Controllers\api\CategorieArticleApiController;
use App\Http\Controllers\api\DataApiController;
use App\Http\Controllers\api\FactureApiController;
use App\Http\Controllers\api\GroupeArticleApiController;
use App\Http\Controllers\api\MobileDataApiController;
use App\Http\Controllers\api\UniteMesureApiController;
use App\Http\Controllers\api\VenteApiController;
use App\Http\Controllers\app\SuperAdmin;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/add-user', [SuperAdmin::class, 'adduser'])->name('add-user.sudo.api');
    Route::post('/access', [SuperAdmin::class, 'access'])->name('access.sudo.api');

    Route::middleware('compteBloque.mdlw')->group(function () {
        Route::middleware('admin.mdlw')->group(function () {
            Route::post('/admin/update/shop', [AdminApiController::class, 'shop'])->name('admin.shop.update.api');
            Route::post('/admin/update', [AdminApiController::class, 'update'])->name('admin.update.api');
            Route::put('/admin/update/pass', [AdminApiController::class, 'update_pass'])->name('admin.update.pass.api');
            Route::resource('unite-mesure', UniteMesureApiController::class);
            Route::post('/taux', [DataApiController::class, 'taux'])->name('taux.api');
            Route::delete('/daccord', [DataApiController::class, 'daccord'])->name('daccord.api');
        });

        Route::resource('groupe-article', GroupeArticleApiController::class);
        Route::resource('categorie-article', CategorieArticleApiController::class);
        Route::resource('articles', ArticlesApiController::class);
        Route::post('articles/import', [ArticlesApiController::class, 'import'])->name('article.import');
        Route::resource('factures', FactureApiController::class);
        Route::resource('ventes', VenteApiController::class);
        Route::get('/statistique', [DataApiController::class, 'statistique'])->name('statistique.api');
        Route::resource('caissier', CaissierApiController::class);


        Route::middleware('caissier.mdlw')->group(function () {
            Route::post('/caissier/update', [Caissier2ApiController::class, 'update'])->name('caissier.update.api');
            Route::put('/caissier/update/pass', [Caissier2ApiController::class, 'update_pass'])->name('caissier.update.pass.api');
        });

        Route::get('/net-a-payer', [DataApiController::class, 'netApayer'])->name('netApayer.api');
        Route::get('/afficher-facture', [DataApiController::class, 'afficherFacture'])->name('afficher-facture.api');
        Route::get('/check-items', [DataApiController::class, 'checkItems'])->name('check-items.api');
        Route::post('/nouvelle-facture', [DataApiController::class, 'nouvelle_facture'])->name('nouvelle-facture.api');
    });
});

Route::post('/app/check-data', [MobileDataApiController::class, 'checkdata']);
Route::post('/app/qr-login', [MobileDataApiController::class, 'qrlogin']);

