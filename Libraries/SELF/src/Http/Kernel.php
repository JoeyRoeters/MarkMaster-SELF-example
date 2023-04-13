<?php
namespace SELF\src\Http;

use SELF\src\Application;
use SELF\src\Helpers\Request\RequestChain;
use SELF\src\Http\Middleware\Middleware;
use SELF\src\Http\Responses\Response;

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

    public function handleRequest(Request $request)
    {
        $response = (new RequestChain($this->app))
            ->setRequest($request)
            ->setStages($this->middleware)
            ->setFinally(fn (Request $request) => $this->sendRequestToRouter($request))
            ->handleChain();

        // handle responses
        if ($response instanceof Response) {
            $response->output();
        }
    }

    public function sendRequestToRouter(Request $request): Response
    {
        return $this->router->handleRoute($request);
    }
}