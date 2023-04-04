<?php
namespace SELF\src\Helpers\Request;

use Closure;
use SELF\src\Container;
use SELF\src\Helpers\Interfaces\Chain\RequestChainableInterface;
use SELF\src\Http\Request;

class RequestChain
{
    private Closure $finally;

    private Request $request;

    private array $stages;

    public function __construct(private Container $container)
    {}

    public function setRequest(Request $request): self
    {
        $this->request = $request;
        return $this;
    }

    public function setStages(array $stages): self
    {
        $this->stages = $stages;
        return $this;
    }

    public function setFinally(Closure $closure): self
    {
        $this->finally = $closure;
        return $this;
    }

    public function handleChain()
    {
        return $this->process(
            $this->request,
            $this->stages
        );
    }

    private function process(Request $request, array $chain): mixed
    {
        if (empty($chain)) {
            $finally = $this->finally;
            return $finally($request);
        }

        /**
         * @var RequestChainableInterface $chainable
         */
        $chainable = $this->container->resolve(
            array_shift($chain)
        );

        return $chainable->handle(
            $request,
            fn (Request $request) => $this->process($request, $chain),
        );
    }
}