<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Manta News Configuration
    |--------------------------------------------------------------------------
    |
    | Hier kan je de configuratie voor de Manta News package aanpassen.
    |
    */

    // Route prefix voor de news module
    'route_prefix' => 'cms/news',

    // Database instellingen
    'database' => [
        'table_name' => 'manta_news',
    ],
    'cat' => [
        'database' => [
            'table_name' => 'manta_newscats',
        ],
    ],
    'join' => [
        'database' => [
            'table_name' => 'manta_newscatjoins',
        ],
    ],

    // Email instellingen
    'email' => [
        'from' => [
            'address' => env('MAIL_FROM_ADDRESS', 'noreply@example.com'),
            'name' => env('MAIL_FROM_NAME', 'Manta News'),
        ],
        'enabled' => true,
        'default_subject' => 'Nieuw nieuwsbericht',
        'default_receivers' => env('MAIL_TO_ADDRESS', 'admin@example.com'),
    ],

    // UI instellingen
    'ui' => [
        'items_per_page' => 25,
        'show_breadcrumbs' => true,
    ],


];
