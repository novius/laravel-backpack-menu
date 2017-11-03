<?php

namespace Novius\Backpack\Menu;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class MenuServiceProvider extends LaravelServiceProvider
{
    const PACKAGE_NAME = 'laravel-backpack-menu';

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $packageDir = dirname(__DIR__);

        $this->publishes([$packageDir.'/config' => config_path('backpack')], 'config');
        $this->publishes([$packageDir.'/resources/lang' => resource_path('lang/vendor/backpack')], 'lang');
        $this->publishes([$packageDir.'/routes' => base_path().'/routes'], 'routes');
        $viewsTargetPath = resource_path('views/vendor/'.static::PACKAGE_NAME);
        $this->publishes([$packageDir.'/resources/views' => $viewsTargetPath], 'views');
        $this->publishes([$packageDir.'/database/migrations' => database_path('migrations')], 'migrations');

        $this->loadMigrationsFrom($packageDir.'/database/migrations');
        $this->loadTranslationsFrom($packageDir.'/resources/lang', static::PACKAGE_NAME);
        $this->loadViewsFrom($packageDir.'/resources/views', static::PACKAGE_NAME);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->setupRoutes();
    }

    /**
     * By default the package uses its own routes defined in /routes/backpack/menu.php
     * But you can easily override these routes by placing a file in your application /routes/backpack/menu.php
     */
    protected function setupRoutes()
    {
        $commonPath = '/routes/backpack/'.static::PACKAGE_NAME.'.php';
        $appRoutesPath = base_path().$commonPath;
        $packageRoutesPath = dirname(__DIR__).$commonPath;
        $this->loadRoutesFrom(file_exists($appRoutesPath) ? $appRoutesPath : $packageRoutesPath);
    }
}
