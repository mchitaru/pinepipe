<?php

namespace App\Providers;

use App\Composers\BreadcrumbComposer;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\View;
use ConsoleTVs\Charts\Registrar as Charts;

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
    public function boot(Charts $charts)
    {
        Schema::defaultStringLength(191);

        view()->composer('*', function($view){

            $_user = \Auth::user();

            $view->with(compact('_user'));
        });

        // Push the breadcrumbs to the view
        View::composer('wiki.*', BreadcrumbComposer::class);    
        
        $charts->register([
            \App\Charts\NewUsersChart::class,
            \App\Charts\DailyUsersChart::class,
            \App\Charts\ActiveUsersChart::class,
        ]);
    }
}
