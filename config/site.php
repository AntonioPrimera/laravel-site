<?php

// config for AntonioPrimera/Site
return [
    /**
     * The list of locales that should be used for translations
     * Make sure to include the default and fallback locale in this list
     */
    'translations' => [
        'locales' => ['en'],
        'missing-translation' => '--',  //the string that should be displayed if a translation is missing
    ],

    /**
     * Site section view component default configuration
     */
    'sections' => [

        //section images will be resized to fit these dimensions
        'image' => [
            'max-width' => 1920,
            'max-height' => 1080,
        ],
    ],

    /**
     * Data migrations configuration
     */
    'data-migrations' => [

        //the path to the directory where site data migrations are stored, relative to the database directory
        'path' => 'site-migrations',
    ],

    /**
     * Media catalog configuration
     */
    'media-catalog' => [
        //the disk where media catalog files are stored
        'disk' => 'media-catalog',
    ],

    'views' => [
        //the root path for the blade views of the site components (relative to the resources/views directory)
        'bladeRootName' => 'components.site',

        //the namespace of the site components
        'componentNamespace' => 'App\\View\\Components\\Site\\',
    ]
];
