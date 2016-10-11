<?php

namespace charlesassets\LaravelPerfectMoney;

use Illuminate\Support\ServiceProvider;

class LaravelPerfectMoneyServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
	
		// Config
		$this->publishes([
			__DIR__ . '/../src/config/perfectmoney.php' => config_path('perfectmoney.php'),
		], 'config');
		
		
		// Views
		$this->loadViewsFrom(__DIR__.'/../src/views', 'perfectmoney');
		
		$this->publishes([
			__DIR__.'/../src/views' => resource_path('views/vendor/perfectmoney'),
		], 'views');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}