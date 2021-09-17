<?php

return [
    'route_prefix' => 'audit',
    'route_domain' => env('APP_URL', 'http://localhost'),

    'enabled' => env('MUATHYE_AUDIT_ENABLED', null),
    'production-enabled' => env('MUATHYE_AUDIT_PRODUCTION_ENABLED', null),

    /*
     |--------------------------------------------------------------------------
     | Storage settings
     |--------------------------------------------------------------------------
     |
     | Audit stores data for session/ajax requests.
     | You can disable this, so the audit stores data in headers/session,
     | but this can cause problems with large data collectors.
     | By default, file storage (in the storage folder) is used. PDO can
     | also be used and for PDO, run the package migrations first.
     |
     */
    'storage' => [
        'enabled'    => true,
        'driver'     => 'file', // file, pdo, custom
        'path'       => storage_path('muathye'), // For file driver
        'connection' => null,   // Leave null for default connection (Redis/PDO)
        'provider'   => '', // Instance of StorageInterface for custom driver
        'hostname'   => '127.0.0.1', // Hostname to use with the "socket" driver
        'port'       => 2304, // Port to use with the "socket" driver
    ],
];