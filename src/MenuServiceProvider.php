<?php

namespace Novius\Menu;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class MenuServiceProvider extends LaravelServiceProvider
{
    const PACKAGE_NAME = 'laravel-menu';

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $packageDir = dirname(__DIR__);

        $configPath = $packageDir.'/config/'.static::PACKAGE_NAME.'.php';
        $configTargetPath = config_path(static::PACKAGE_NAME.'.php');
        $this->publishes([$configPath => $configTargetPath], 'config');

        $translationPath = $packageDir.'/resources/lang';
        $translationTargetPath = resource_path('lang/vendor/'.static::PACKAGE_NAME);
        $this->publishes([$translationPath => $translationTargetPath], 'lang');

        $viewsPath = $packageDir.'/resources/views';
        $viewsTargetPath = resource_path('views/vendor/'.static::PACKAGE_NAME);
        $this->publishes([$viewsPath => $viewsTargetPath], 'views');

        $routesPath = $packageDir.'/routes/backpack/'.static::PACKAGE_NAME.'.php';
        $routesTargetPath = base_path().'/routes/backpack/'.static::PACKAGE_NAME.'.php';
        $this->publishes([$routesPath => $routesTargetPath], 'routes');

        $migrationsPath = $packageDir.'/database/migrations';
        $migrationsTargetPath = database_path('migrations');
        $this->publishes([$migrationsPath => $migrationsTargetPath], 'migrations');

        $this->loadMigrationsFrom($packageDir.'/database/migrations');
        $this->loadTranslationsFrom($translationPath, static::PACKAGE_NAME);
        $this->loadViewsFrom($viewsPath, static::PACKAGE_NAME);
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
     * By default the package uses its own routes defined in /routes/backpack/laravel-menu.php
     * But you can easily override these routes by placing a file in your application /routes/backpack/laravel-menu.php
     */
    protected function setupRoutes()
    {
        $commonPath = '/routes/backpack/'.static::PACKAGE_NAME.'.php';
        $appRoutesPath = base_path().$commonPath;
        $packageRoutesPath = dirname(__DIR__).$commonPath;
        $this->loadRoutesFrom(file_exists($appRoutesPath) ? $appRoutesPath : $packageRoutesPath);
    }
}
