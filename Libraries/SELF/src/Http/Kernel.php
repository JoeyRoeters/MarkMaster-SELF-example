<?php
namespace SELF\src\Http;

use SELF\src\Application;
use SELF\src\Helpers\Request\RequestChain;
use SELF\src\Http\Middleware\Middleware;
use SELF\src\Http\Responses\AssetResponse;
use SELF\src\Http\Responses\Response;

class Kernel
{
    public function __construct(
        protected Application $app,
        protected Router $router,
    ) {
    }

    /**
     * @return Middleware[]
     */
    public function getMiddleware(): array
    {
        return [];
    }

    public function handleRequest(Request $request): void
    {
        if ($this->isAsset($request)) {
            $response = new AssetResponse($request->getUri()->getPath());
        } else {
            $response = (new RequestChain())
                ->setRequest($request)
                ->setStages($this->getMiddleware())
                ->setFinally(fn (Request $request) => $this->sendRequestToRouter($request))
                ->handleChain();
        }

        // handle responses
        if ($response instanceof Response) {
            $response->output();
        }
    }

    public function sendRequestToRouter(Request $request): Response
    {
        return $this->router->handleRoute($request);
    }


    private function isAsset(Request $request): bool
    {
        return str_contains($request->getUri()->getPath(), 'Public/assets');
    }

}