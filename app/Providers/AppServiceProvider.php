<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\View;

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

        view()->composer('*', function($view){

            $user = \Auth::user();

            $_timesheets = $user?$user->timesheets()->orderBy('started_at', 'desc')->orderBy('updated_at', 'desc')->get():collect();
            
            $view->with(compact('user', '_timesheets'));
        });
        // View::share('key', 'value');
    }
}
