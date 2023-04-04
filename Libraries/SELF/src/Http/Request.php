<?php
namespace SELF\src\Http;

use SELF\src\Helpers\Enums\MethodEnum;
use SELF\src\Helpers\Interfaces\Message\RequestInterface;
use SELF\src\Helpers\Interfaces\Message\UriInterface;

class Request extends Message implements RequestInterface
{
    protected function __construct(
        protected UriInterface $uri,
        protected MethodEnum $method = MethodEnum::GET
    ) {
        parent::__construct();
    }

    public function getRequestTarget(): string
    {
        $target = $this->uri->getPath();
        if ($this->uri->getQuery()) {
            $target .= '?' . $this->uri->getQuery();
        }

        return $target;
    }

    public function withRequestTarget(UriInterface $requestTarget): self
    {
        $new = clone $this;
        $new->uri = $this->uri->withPath($requestTarget);

        return $new;
    }

    public function getMethod(): MethodEnum
    {
        return $this->method;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(string $uri, bool $preserveHost = false): self
    {
        $new = clone $this;
        $new->uri = $uri;

        return $new;
    }

    public function withMethod(MethodEnum $method): self
    {
        $new = clone $this;
        $new->method = $method;

        return $new;
    }
}