<?php

namespace Muathye\Audit;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Muathye\Audit\Middleware\InjectAudit;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../config/muathye-audit.php';
        $this->mergeConfigFrom($configPath, 'muathye-audit');

        $this->loadRoutesFrom(realpath(__DIR__ . '/muathye-audit-routes.php'));

    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__ . '/../config/muathye-audit.php';
        $this->publishes([$configPath => $this->getConfigPath()], 'config');

        $this->app->singleton(
            'audit', function ($app) {
                $audit = new Audit($app);

                return $audit;
            }
        );

        $this->registerMiddleware(InjectAudit::class);

        $this->app->make('config')->set(
            'logging.channels.muathye-audit', [
                'driver' => 'single',
                'path' => storage_path('muathye/audit.log'),
                // 'level' => 'debug',
            ]
        );
    }

    /**
     * Get the active router.
     *
     * @return Router
     */
    protected function getRouter()
    {
        return $this->app['router'];
    }

    /**
     * Get the config path
     *
     * @return string
     */
    protected function getConfigPath()
    {
        return config_path('muathye-audit.php');
    }

    /**
     * Publish the config file
     *
     * @param  string $configPath
     */
    protected function publishConfig($configPath)
    {
        $this->publishes([$configPath => config_path('muathye-audit.php')], 'config');
    }

    /**
     * Register the Debugbar Middleware
     *
     * @param  string $middleware
     */
    protected function registerMiddleware($middleware)
    {
        $kernel = $this->app[Kernel::class];
        $kernel->pushMiddleware($middleware);
    }
}
