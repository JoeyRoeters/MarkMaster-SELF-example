<?php

namespace SELF\src\Http;

use SELF\src\Helpers\Enums\MethodEnum;
use SELF\src\Helpers\Interfaces\Message\ServerRequestInterface;
use SELF\src\Helpers\Request\Uri;

class ServerRequest extends Request implements ServerRequestInterface
{

    public function __construct(
        protected array $serverParams = [],
        protected array $cookieParams = [],
        protected array $queryParams = [],
        protected array $parsedBody = [],
        protected array $attributes = [],
    ) {
        parent::__construct(
            Uri::createFromString($serverParams['REQUEST_URI']),
            MethodEnum::from($serverParams['REQUEST_METHOD'])
        );

        $this->protocolVersion = $serverParams['SERVER_PROTOCOL'];
        $this->headers = array_map(fn($value) => explode(',', $value), getallheaders());
    }

    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    public function getCookieParams(): array
    {
        return $this->cookieParams;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getParsedBody(): array
    {
        return $this->parsedBody;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute($name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    public function withCookieParams(array $cookies): self
    {
        $new = clone $this;
        $new->cookieParams = $cookies;

        return $new;
    }

    public function withQueryParams(array $query): self
    {
        $new = clone $this;
        $new->queryParams = $query;

        return $new;
    }

    public function withParsedBody($data): self
    {
        $new = clone $this;
        $new->parsedBody = $data;

        return $new;
    }

    public function withAttribute($name, $value): self
    {
        $new = clone $this;
        $new->attributes[$name] = $value;

        return $new;
    }

    public function withoutAttribute($name): self
    {
        $new = clone $this;
        unset($new->attributes[$name]);

        return $new;
    }

    public static function fromGlobals(): self
    {
        return new self(
            $_SERVER,
            $_COOKIE,
            $_GET,
            $_POST,
            $_SESSION
        );
    }
}