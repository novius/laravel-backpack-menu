<?php

namespace Novius\Menu;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class MenuServiceProvider extends LaravelServiceProvider
{
    const CONFIG_FILE_NAME = 'laravel-menu';

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $packageDir = dirname(__DIR__);

        $configPath = $packageDir.'/config/'.static::CONFIG_FILE_NAME.'.php';
        $configTargetPath = config_path(static::CONFIG_FILE_NAME.'.php');

        $translationPath = $packageDir.'/resources/lang';
        $translationTargetPath = resource_path('lang/vendor/'.static::CONFIG_FILE_NAME);

        $viewsPath = $packageDir.'/resources/views';
        $viewsTargetPath = resource_path('views/vendor/'.static::CONFIG_FILE_NAME);

        $this->publishes([
            $configPath => $configTargetPath,
            $translationPath => $translationTargetPath,
            $viewsPath => $viewsTargetPath,
        ], static::CONFIG_FILE_NAME);

        $this->loadMigrationsFrom($packageDir.'/database/migrations');
        $this->loadTranslationsFrom($translationPath, static::CONFIG_FILE_NAME);
        $this->loadViewsFrom($viewsPath, static::CONFIG_FILE_NAME);
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
        $appRoutesPath = '/../routes/backpack/laravel-menu.php';
        $packageRoutesPath = dirname(__DIR__).'/routes/backpack/laravel-menu.php';

        if (file_exists(base_path().$appRoutesPath)) {
            $packageRoutesPath = base_path().$appRoutesPath;
        }

        $this->loadRoutesFrom($packageRoutesPath);
    }
}
