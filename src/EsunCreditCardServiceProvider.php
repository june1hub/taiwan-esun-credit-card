<?php

namespace June1hub\TaiwanEsunCreditCard;

use Illuminate\Support\ServiceProvider;

class EsunCreditCardServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // $this->registerConfigs();
        }
        $this->registerResources();
        
        // $this->loadRoutesFrom(__DIR__.'/../routes/routes.php');
    }

    protected function registerConfigs()
    {
        $this->publishes([
            __DIR__ . '/../config/esun.php' => config_path('esun.php')
        ], 'creditcard');
    }

    protected function registerResources()
    {        
        // $this->loadViewsFrom(__DIR__ . '/../resources/views', 'esun');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'esun');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/esun/creditCard'),
        ]);
    }

    public function register()
    {   
        // $this->app->singleton('esun-credit-card', function ($app) {
        //     return new EsunCreditCard();
        // });
    }
}