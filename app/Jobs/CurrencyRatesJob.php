<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Currency;

class CurrencyRatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $url = 'https://openexchangerates.org/api/latest.json?';
        $url .= 'app_id=' . config('services.open_exchange.app_id');

        $client = new \GuzzleHttp\Client(['http_errors' => false]);
        $body   = $client->request('GET', $url);

        if ($body->getStatusCode() === 200) {

            $rates = json_decode($body->getBody());

            Currency::updateRates($rates);
        }
    }
}
