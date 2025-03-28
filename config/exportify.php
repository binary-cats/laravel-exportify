<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Export Policy
    |--------------------------------------------------------------------------
    |
    | Here you can configure the policy for the exports.
    |
    */
    'policy' => [
        /* Name of the policy method to check */
        'name' => 'view',

        /* Default value if the policy does not exist */
        'default' => false,
    ],

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
        /* Whether to enable export history */
        'enabled' => env('EXPORTIFY_HISTORY_ENABLED', true),

        /* Name of the table to store export history */
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
        /* Whether to enable the dashboard */
        'enabled' => env('EXPORTIFY_DASHBOARD_ENABLED', true),

        /* Path to the dashboard */
        'path' => env('EXPORTIFY_DASHBOARD_PATH', 'exportify'),

        /* Middleware to protect the dashboard */
        'middleware' => ['web', 'auth'],
    ],
];
