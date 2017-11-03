<?php
/**
 * Defines the routes of Backpack
 */
Route::group([
    'namespace' => 'Novius\Backpack\Menu\Http\Controllers\Admin\Menu',
    'prefix' => config('backpack.laravel-backpack-menu.prefix', 'admin'),
    'middleware' => ['web', 'admin'],
], function () {
    CRUD::resource('menu', 'MenuController');
    CRUD::resource('item', 'ItemController');
});
