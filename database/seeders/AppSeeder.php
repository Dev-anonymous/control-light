<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Boutique;
use App\Models\Categorie;
use App\Models\CategorieArticle;
use App\Models\CategorieBoutique;
use App\Models\Devise;
use App\Models\GroupeArticle;
use App\Models\Marque;
use App\Models\Shop;
use App\Models\Taux;
use App\Models\UniteMesure;
use App\Models\User;
use App\Models\ValidationBoutique;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if (!Devise::all()->first()) {
            $i = 1;
            foreach (['CDF', 'USD'] as $dev) {
                $d = new Devise();
                $d->devise = $dev;
                $d->save();
                if ($i == 1) {
                    $i = 20;
                    Taux::create(['devise_id' => 1, 'devise2_id' => 2, 'taux' => 0.0005]);
                } else {
                    Taux::create(['devise_id' => 2, 'devise2_id' => 1, 'taux' => 2000]);
                }
            }
        }

        foreach ([
            'Alimentation' => ['Epices', 'Legumes', 'Aliment', 'Autre'],
            'Habillement' => ['Chaussure Dame', 'Chaussure homme', 'Lingerie', 'Divers'],
            'Quicaillerie' => ['Composant Electronique', 'Ordinateur'],
            'Divers' => ['Autres']
        ] as $gr => $cat) {
            if (!GroupeArticle::where(['groupe' => $gr])->first()) {
                $g =  GroupeArticle::create(['groupe' => $gr]);
                foreach ($cat as $el) {
                    CategorieArticle::create([
                        'categorie' => $el,
                        'groupe_article_id' => $g->id
                    ]);
                }
            }
        }

        Shop::create([
            'shop' => 'Ma Boutique',
            'date_creation' => now()
        ]);

        foreach (['Pièce', 'Mètre', 'Carton', 'Boite'] as $el) {
            if (!UniteMesure::where(['unite_mesure' => $el])->first()) {
                UniteMesure::create(['unite_mesure' => $el]);
            }
        }

        if (!User::first()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@admin.admin',
                'user_role' => 'admin',
                'password' => Hash::make('admin')
            ]);
        }
    }
}
