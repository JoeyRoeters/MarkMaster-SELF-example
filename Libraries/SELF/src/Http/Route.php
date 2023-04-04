<?php

namespace SELF\src\Http;

class Route
{
    public function __construct(
        //todo request method needs to be enum
        private         $requestMethod,
        private string  $uri,
        private string  $targetClass,
        private ?string $targetMethod,
        private array   $middleware = [],
    )
    {

    }

    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    public function getTargetClass(): string
    {
        return $this->targetClass;
    }

    public function getTargetMethod(): string
    {
        return $this->targetMethod;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getMiddleware(): array
    {
        return $this->middleware;
    }
}