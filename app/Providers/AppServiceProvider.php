<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // \Braintree_Configuration::environment(config('services.braintree.environment'));
        // \Braintree_Configuration::merchantId(config('services.braintree.merchant_id'));
        // \Braintree_Configuration::publicKey(config('services.braintree.public_key'));
        // \Braintree_Configuration::privateKey(config('services.braintree.private_key'));        
    }
}
