<?php

namespace Muathye\Audit\Middleware;

use Error;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Muathye\Audit\Audit;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class InjectAudit
{
    /**
     * The App container
     *
     * @var Container
     */
    protected $container;

    /**
     * The Audit instance
     *
     * @var Audit
     */
    protected $audit;

    /**
     * Create a new middleware instance.
     *
     * @param  Container $container
     * @param  Audit $audit
     */
    public function __construct(Container $container, Audit $audit)
    {
        $this->container = $container;
        $this->audit = $audit;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$this->audit->isEnabled()) {
            return $next($request);
        }

        $this->audit->boot();

        try {
            /**
             * Get response
             * 
             * @var \Illuminate\Http\Response $response
             */
            $response = $next($request);
        } catch (Exception $e) {
            $response = $this->handleException($request, $e);
        } catch (Error $error) {
            $e = new FatalThrowableError($error);
            $response = $this->handleException($request, $e);
        }

        // Log request
        
        return $response;
    }

    /**
     * It works by keeping the PHP process alive to run the
     * tasks after closing the connection with the browser.
     * 
     * @param Request  $request  .
     * @param Response $response .
     * 
     * @return void
     */
    public function terminate($request, $response)
    {
        // ddd(resolve('audit')->isEnabled());
        // Perform tasks after response as been sent to the client
        if (resolve('audit')->isEnabled()) {
            audit();
        }
    }

    /**
     * Handle the given exception.
     *
     * (Copied from Illuminate\Routing\Pipeline by Taylor Otwell)
     *
     * @param mixed     $passable .
     * @param Exception $e        .
     *
     * @return mixed
     * @throws Exception
     */
    protected function handleException($passable, Exception $e)
    {
        if (! $this->container->bound(ExceptionHandler::class) || ! $passable instanceof Request) {
            throw $e;
        }

        $handler = $this->container->make(ExceptionHandler::class);

        $handler->report($e);

        return $handler->render($passable, $e);
    }
}
