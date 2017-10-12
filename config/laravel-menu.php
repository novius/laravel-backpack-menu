<?php

return [
    /*
     * The prefix is used by backpack to generate routes. The default value is "admin".
     */

    'prefix' => '',

    /*
     * Entities linkable by an menu item. For instance here we have:
     *  1. Forms
     *  2. Pages
     *
     * So the forms and pages of your application will be listed and linkable by an item menu.
     *
     * It must contains pairs of:
     *          full-class-name => prefix for the list in backoffice
     *
     * The prefix will be a parameter of the laravel function trans().
     */

    'linkableObjects' => [
        'App\Models\Form\Form' => 'configuration.items.form',
        'App\Models\Page' => 'Page',
    ],

    /*
     * Sometimes you need to link items that are not objects.
     * This config allows you to link urls.
     * "contact" will produce something like: "http://yourbaseurl.com/contact"
     */
    'linkableUrls' => [
        'contact' => 'Autre maniere de linker le form contact',
    ],
];
