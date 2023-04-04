<?php
namespace SELF\src\Http;

use SELF\src\Application;
use SELF\src\Helpers\Request\RequestChain;
use SELF\src\Http\Middleware\Middleware;

class Kernel
{
    /**
     * @var Middleware[] $middleware
     */
    protected array $middleware = [];

    public function __construct(
        protected Application $app,
        protected Router $router,
    ) {
    }

    public function handleRequest(Request $request): void
    {
        (new RequestChain($this->app))
            ->setRequest($request)
            ->setStages($this->middleware)
            ->setFinally(fn (Request $request) => $this->sendRequestToRouter($request))
            ->handleChain();
    }

    public function sendRequestToRouter(Request $request)
    {
        var_dump('Dit heeft gewerkt');
        die();
    }
}