<?php

namespace zfhassaan\easypaisa\provider;

use zfhassaan\easypaisa\Facade\EasypaisaFacade;
use zfhassaan\easypaisa\Easypaisa;

class EasyPaisaServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap the application Service.
     */
    public function boot() {
        if($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/config.php'=>config_path('Easypaisa.php'),
            ],'config');
        }
    }

    /**
     * Register the application Services in Service Provider
     */
    public function register()
    {
        //Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../../config/config.php','easypaisa');

        // Register the main class to use with the facade.
        $this->app->singleton('easypaisa', function() {
            return new Easypaisa;
        });
    }
}