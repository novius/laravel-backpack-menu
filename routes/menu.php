<?php
/**
 * Defines the routes of Backpack
 */
Route::group([
    'namespace' => 'Novius\Menu\Http\Controllers\Admin\Menu',
    'prefix' => config('laravel-menu.prefix', 'admin'),
    'middleware' => ['web', 'admin'],
], function () {
    CRUD::resource('menu', 'MenuController');
    CRUD::resource('item', 'ItemController');
});
