## Laravel Requests Audit

This is a package to audit all http requests in your project.

## Installation

Require this package with composer.

```shell
composer require muath-ye/audit
```

Laravel uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider.

The Audit will be enabled automatically but you can stop it when change `MUATHYE_AUDIT_ENABLED` is `false`.

> If you use a catch-all/fallback route, make sure you load the Muathye ServiceProvider before your own App ServiceProviders.

### Laravel without auto-discovery

If you don't use auto-discovery, add the ServiceProvider to the providers array in config/app.php

```php
Muathye\Audit\ServiceProvider::class,
```

#### Copy the package config to your local config with the publish command:

```shell
php artisan vendor:publish --provider="Muathye\Audit\ServiceProvider"
```

## Usage

update your ```.env``` file as following:

```php
MUATHYE_AUDIT_ENABLED=true
```

## Enabling/Disabling on run time
You can enable or disable the audit during run time.

```php
\Audit::enable();
\Audit::disable();
```

Note ```Audit::class``` is registered as an aliese for ```Muathye\Audit\Support\Audit::class```

Use can use helper functions :

```php
enableAudit();
disableAudit();
```
