<?php

namespace SELF\src\Http;

use SELF\src\Exceptions\Route\RoutingMethodNotFoundException;
use SELF\src\Helpers\Enums\MethodEnum;
use SELF\src\Helpers\Request\Uri;
use SELF\src\Http\Middleware\Middleware;

/**
 * @method static self GET(string $path, string $targetClass, string $targetMethod, array $middleware = [])
 * @method static self POST(string $path, string $targetClass, string $targetMethod, array $middleware = [])
 * @method static self PUT(string $path, string $targetClass, string $targetMethod, array $middleware = [])
 * @method static self DELETE(string $path, string $targetClass, string $targetMethod, array $middleware = [])
 * @method static self PATCH(string $path, string $targetClass, string $targetMethod, array $middleware = [])
 * @method static self OPTIONS(string $path, string $targetClass, string $targetMethod, array $middleware = [])
 * @method static self HEAD(string $path, string $targetClass, string $targetMethod, array $middleware = [])
 */
class Route
{
    private function __construct(
        private readonly MethodEnum $requestMethod,
        private readonly string     $path,
        private readonly string     $targetClass,
        private readonly string     $targetMethod,
        private readonly array      $middleware = [],
    ) {}

    public static function __callStatic(string $name, array $arguments): self
    {
        $requestMethod = MethodEnum::tryFrom($name);

        if ($requestMethod === null) {
            throw new RoutingMethodNotFoundException();
        }

        return new self(
            $requestMethod,
            ...$arguments,
        );
    }

    public function getRequestMethod(): MethodEnum
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

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return Middleware[]
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    public function resolveInlineParameters(Uri $uri): array
    {
        $inlineParams = $this->getInlineParameters();
        if (empty($inlineParams)) {
            return [];
        }

        $parts = explode('/', $uri->getPath());
        $params = array_values(
            array_filter($parts, fn (string $substr) => ! str_contains($this->getPath(), $substr))
        );

        return array_map_with_keys(
            fn (string $param, int $index) => [$inlineParams[$index] => $param],
            $params,
        );
    }

    public function getInlineParameters(): array
    {
        preg_match_all('((?<={)(.*?)(?=}))', $this->getPath() . '/', $matches);
        return $matches[0];
    }

    public function hasMiddleware(): bool
    {
        return ! empty($this->getMiddleware());
    }

    public function isMatch(Request $request): bool
    {
        $pathFound = preg_match($this->getMatchablePath(), $request->getUri()->getPath()) === 1;
        return $pathFound && $request->getMethod() === $this->getRequestMethod();
    }

    /**
     * Converts inline parameters to regex expression, in order to be matched
     * to actual route.
     *
     * @return string
     */
    private function getMatchablePath(): string
    {
        $inlineParams = array_map(fn (string $param) => '{' . $param . '}', $this->getInlineParameters());
        return '(^' . str_replace($inlineParams, '.*?', $this->getPath()) . '$)';
    }
}