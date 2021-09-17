<?php

namespace Muathye\Audit;

use Muathye\Audit\MuahtyeAudit;

use Muathye\Audit\Storage\FilesystemStorage;
use Muathye\Audit\Storage\DbStorage;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Request;

/**
 * Debug bar subclass which adds all without Request and with LaravelCollector.
 * Rest is added in Service Provider
 *
 * @method void emergency(...$message)
 * @method void alert(...$message)
 * @method void critical(...$message)
 * @method void error(...$message)
 * @method void warning(...$message)
 * @method void notice(...$message)
 * @method void info(...$message)
 * @method void debug(...$message)
 * @method void log(...$message)
 */
class Audit extends MuahtyeAudit
{
    /**
     * The Laravel application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Normalized Laravel Version
     *
     * @var string
     */
    protected $version;

    /**
     * True when booted.
     *
     * @var bool
     */
    protected $booted = false;

    /**
     * True when enabled, false disabled an null for still unknown
     *
     * @var bool
     */
    protected $enabled = null;

    /**
     * Create new instance.
     *
     * @param Application $app .
     */
    public function __construct($app = null)
    {
        if (!$app) {
            $app = app();   //Fallback when $app is not given
        }
        $this->app = $app;
        $this->version = $app->version();
    }

    /**
     * Boot the Audit.
     * 
     * @return void
     */
    public function boot()
    {
        if ($this->booted) {
            return;
        }

        /**
         * Use Audit instance.
         * 
         * @var \Muathye\Audit\Audit $audit
         */
        $audit = $this;

        /**
         * Use Laravel instance.
         * 
         * @var Application $app
         */
        $app = $this->app;

        $this->selectStorage($audit);

        $this->booted = true;
    }

    /**
     * Choose Storage.
     *
     * @param MuahtyeAudit $muathye_audit Debugbar\Debugbar
     *
     * @return void
     */
    protected function selectStorage(MuahtyeAudit $muathye_audit)
    {
        $config = $this->app['config'];
        if ($config->get('muathye-audit.storage.enabled')) {
            $driver = $config->get('muathye-audit.storage.driver', 'file');

            switch ($driver) {
                case 'pdo':
                    $connection = $config->get('muathye-audit.storage.connection');
                    $table = $this->app['db']->getTablePrefix() . 'muathye_audit';
                    $pdo = $this->app['db']->connection($connection)->getPdo();
                    $storage = new DbStorage($pdo, $table);
                    break;
                case 'custom':
                    $class = $config->get('muathye-audit.storage.provider');
                    $storage = $this->app->make($class);
                    break;
                case 'file':
                default:
                    $path = $config->get('muathye-audit.storage.path');
                    $storage = new FilesystemStorage($this->app['files'], $path);
                    break;
            }

            $muathye_audit->setStorage($storage);
        }
    }

    /**
     * Check if the Audit is enabled.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        if ($this->enabled === null) {
            $config = $this->app['config'];
            $configEnabled = value($config->get('muathye-audit.enabled'));

            if ($configEnabled === null) {
                $configEnabled = $config->get('app.debug')
                || value($config->get('muathye-audit.production-enabled'));
            }

            $this->enabled = $configEnabled && !$this->app->runningInConsole() && !$this->app->environment('testing');
        }

        return $this->enabled;
    }

    /**
     * Check if this is a request to the Audit OpenHandler.
     *
     * @return bool
     */
    protected function isAuditRequest()
    {
        return $this->app['request']->segment(1) == $this->app['config']->get('muathye-audit.route_prefix');
    }

    /**
     * Check if this is a json request.
     *
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @return bool
     */
    protected function isJsonRequest(Request $request)
    {
        // If XmlHttpRequest or Live, return true
        if ($request->isXmlHttpRequest() || $request->headers->get('X-Livewire')) {
            return true;
        }

        // Check if the request wants Json
        $acceptable = $request->getAcceptableContentTypes();
        return (isset($acceptable[0]) && $acceptable[0] == 'application/json');
    }

    /**
     * Enable the Debugbar and boot, if not already booted.
     * 
     * @return void
     */
    public function enable()
    {
        $this->enabled = true;

        if (!$this->booted) {
            $this->boot();
        }
    }

    /**
     * Disable the Audit.
     * 
     * @return void
     */
    public function disable()
    {
        $this->enabled = false;
    }
    
    /**
     * The Audit.
     * 
     * @return void
     */
    public function audit()
    {
        Log::channel('muathye-audit')->info('Request Uri => ' . request()->getUri());
        Log::channel('muathye-audit')->info('Client Ip => ' . request()->getClientIp());
        Log::channel('muathye-audit')->alert('User Agent => ' . request()->userAgent());
        Log::channel('muathye-audit')->info(
            '============================================================'
        );
    }

    /**
     * TODO:ByMuathye;
     * Magic calls for adding messages
     *
     * @param string $method .
     * @param array  $args   .
     *
     * @return mixed|void
     */
    public function __call($method, $args)
    {
        $messageLevels = ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug', 'log'];
        if (in_array($method, $messageLevels)) {
            foreach ($args as $arg) {
                $this->addMessage($arg, $method);
            }
        }
    }

    /**
     * TODO:ByMuathye;
     * Check the version of Laravel
     *
     * @param string $version
     * @param string $operator (default: '>=')
     * @return boolean
     */
    protected function checkVersion($version, $operator = ">=")
    {
        return version_compare($this->version, $version, $operator);
    }
}
