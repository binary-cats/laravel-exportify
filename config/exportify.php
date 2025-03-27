<?php

// config for BinaryCats/Exportify
return [
    /*
    |--------------------------------------------------------------------------
    | Export History
    |--------------------------------------------------------------------------
    |
    | Here you can configure whether to store export history in the database.
    | If enabled, all exports will be logged with their status and metadata.
    |
    */
    'history' => [
        'enabled' => env('EXPORTIFY_HISTORY_ENABLED', true),
        'table' => 'export_history',
    ],

    /*
    |--------------------------------------------------------------------------
    | Export Dashboard
    |--------------------------------------------------------------------------
    |
    | Here you can configure the dashboard settings.
    |
    */
    'dashboard' => [
        'enabled' => env('EXPORTIFY_DASHBOARD_ENABLED', true),
        'path' => env('EXPORTIFY_DASHBOARD_PATH', 'exportify'),
        'middleware' => ['web', 'auth'],
    ],
];
