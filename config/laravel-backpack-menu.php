<?php

return [
    /*
     * The prefix is used by backpack to generate routes. The default value is "admin".
     */
    'prefix' => 'admin',

    /*
     * Entities linkable by an menu item. For instance "Pages".
     * So the pages of your application will be listed and linkable by an item menu.
     *
     * It must contain pairs of:
     *      full-class-name => prefix for the list in backoffice
     *
     * The "prefix for the list in backoffice" will be the parameter of the laravel function trans().
     *      For instance: 'App\Models\Page' => 'path.to.translation.page'
     *
     * Warning: The models listed below must use the trait LinkedItems and optionally override methods to suit their needs.
     */

    'linkableObjects' => [
        'App\Models\Page' => 'Page',
    ],

    /*
     * Sometimes you need to link items that are not objects.
     *
     * This config allows you to link routes.
     *     For instance: 'contact' => 'Page contact'
     *
     * "contact" will be the parameter of the laravel function route().
     * "Page contact" will be the parameter of the laravel function trans().
     */
    'linkableUrls' => [

    ],

    /*
     * Maximum hierarchy level to which the elements can be nested
     */
    'max_nesting' => 2,
];
