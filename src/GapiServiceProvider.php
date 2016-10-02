<?php

namespace Gtk\Gapi;

use Illuminate\Support\ServiceProvider;

class GapiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('api-response', function () {
            return $this->app->make('Gtk\Gapi\ApiResponse');
        });
    }
}
