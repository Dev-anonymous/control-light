<?php

namespace App\Console\Commands;

use App\Models\Config;
use App\Models\Devise;
use App\Models\Taux;
use App\Models\TauxOnline;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateTaux extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'taux:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update rates list';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $q['app_id'] = 'c23e55d1957747cbbe0354a4ab7d3a54';
        $q['symbols'] = 'CDF';
        $response = Http::get('https://openexchangerates.org/api/latest.json', $q);

        $ok = 0;
        if (!$response->failed()) {
            $body = $response->json();
            if ('USD' == @$body['base']) {
                $tx = @$body['rates']['CDF'];
                if ($tx) {
                    $usd_cdf = round($tx, 6);
                    $cdf_usd = round(1 / $tx, 6);
                    $dusd = Devise::where('devise', 'USD')->first();
                    $dcdf = Devise::where('devise', 'CDF')->first();
                    $conf = Config::all();
                    foreach ($conf as $el) {
                        $devise_auto = getConfig('devise_auto', $el->compte_id);
                        if ($devise_auto == 1) {
                            Taux::where([
                                'devise_id' => $dusd->id,
                                'devise2_id' => $dcdf->id,
                                'compte_id' => $el->compte_id
                            ])->update(['taux' => $usd_cdf, 'maj' => now('Africa/Lubumbashi')]);
                            Taux::where([
                                'devise_id' => $dcdf->id,
                                'devise2_id' => $dusd->id,
                                'compte_id' => $el->compte_id,
                            ])->update(['taux' => $cdf_usd, 'maj' => now('Africa/Lubumbashi')]);
                        }
                    }

                    $to = TauxOnline::first();
                    if ($to) {
                        $to->update([
                            'taux' => "1 CDF = $cdf_usd USD #1 USD = $usd_cdf CDF",
                            'maj' => now('Africa/Lubumbashi')
                        ]);
                    } else {
                        TauxOnline::create([
                            'taux' => "1 CDF = $cdf_usd USD #1 USD = $usd_cdf CDF",
                            'maj' => now('Africa/Lubumbashi')
                        ]);
                    }

                    $ok = 1;
                }
            }
        }
        return $ok;
    }
}
