<?php

namespace SELF\src\Http\Middleware;

class MiddlewareParameters
{
    public function __construct(protected array $params = [])
    {}

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->params;
    }
}