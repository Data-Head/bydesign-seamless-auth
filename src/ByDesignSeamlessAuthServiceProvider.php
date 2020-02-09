<?php
namespace DataHead\ByDesignSeamlessAuth;

use Illuminate\Support\ServiceProvider;

class ByDesignSeamlessAuthServiceProvider extends ServiceProvider {
    public function boot() {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->publishes([
            __DIR__ . '/config/bydesign-seamless-auth.php' => config_path('bydesign-seamless-auth.php')
        ]);
    }

    public function register() {
        $this->mergeConfigFrom(__DIR__ . '/config/bydesign-seamless-auth.php', 'bydesign-seamless-auth');
    }
}

