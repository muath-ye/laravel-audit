<?php

$routeConfig = [
    'namespace' => 'Muathye\Audit\Controllers',
    'prefix' => app('config')->get('muathye-audit.route_prefix'),
    'domain' => app('config')->get('muathye-audit.route_domain'),
    'middleware' => [\Muathye\Audit\Middleware\InjectAudit::class],
];

app('router')->group($routeConfig, function ($router) {
    $router->get('developer', [
        'uses' => 'AuditController@developer',
        'as' => 'audit.developer',
    ]);

    // $router->get('open', [
    //     'uses' => 'OpenHandlerController@handle',
    //     'as' => 'audit.openhandler',
    // ]);

    // $router->get('clockwork/{id}', [
    //     'uses' => 'OpenHandlerController@clockwork',
    //     'as' => 'audit.clockwork',
    // ]);

    // $router->get('telescope/{id}', [
    //     'uses' => 'TelescopeController@show',
    //     'as' => 'audit.telescope',
    // ]);

    // $router->get('assets/stylesheets', [
    //     'uses' => 'AssetController@css',
    //     'as' => 'audit.assets.css',
    // ]);

    // $router->get('assets/javascript', [
    //     'uses' => 'AssetController@js',
    //     'as' => 'audit.assets.js',
    // ]);

    // $router->delete('cache/{key}/{tags?}', [
    //     'uses' => 'CacheController@delete',
    //     'as' => 'audit.cache.delete',
    // ]);
});
