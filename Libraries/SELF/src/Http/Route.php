<?php

namespace SELF\src\Http;

use SELF\src\Exceptions\Route\RoutingMethodNotFoundException;
use SELF\src\Helpers\Enums\MethodEnum;
use SELF\src\Helpers\Request\Uri;

/**
 * @method static self GET(string $path, string $targetClass, string $targetMethod, array $middleware = [])
 * @method static self POST(string $path, string $targetClass, string $targetMethod, array $middleware = [])
 * @method static self PUT(string $path, string $targetClass, string $targetMethod, array $middleware = [])
 * @method static self DELETE(string $path, string $targetClass, string $targetMethod, array $middleware = [])
 * @method static self PATCH(string $path, string $targetClass, string $targetMethod, array $middleware = [])
 * @method static self OPTIONS(string $path, string $targetClass, string $targetMethod, array $middleware = [])
 * @method static self HEAD(string $path, string $targetClass, string $targetMethod, array $middleware = [])
 *
 */
class Route
{
    private function __construct(
        private MethodEnum $requestMethod,
        private string     $path,
        private string     $targetClass,
        private ?string    $targetMethod,
        private array      $middleware = [],
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

    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    public function resolveInlineParameters(Uri $uri): array
    {
        $parts = explode('/', $uri->getPath());
        $params = array_values(
            array_filter($parts, fn (string $substr) => ! str_contains($this->getPath(), $substr))
        );
        $inlineParams = $this->getInlineParameters();

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

    public function isMatch(Uri $uri): bool
    {
        return preg_match($this->getMatchablePath(), $uri->getPath()) === 1;
    }

    public function withoutMiddleware(): self
    {
        $new = clone $this;
        $this->middleware = [];
        return $new;
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
        return '(' . str_replace($inlineParams, '.*?', $this->getPath()) . ')';
    }
}