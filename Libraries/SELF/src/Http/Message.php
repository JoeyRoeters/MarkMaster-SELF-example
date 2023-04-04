<?php

namespace SELF\src\Http;

use SELF\src\Helpers\Interfaces\Message\MessageInterface;

class Message implements MessageInterface
{
    public function __construct(
        protected string $protocolVersion = '1.1',
        protected array $headers = [],
        protected string $body = ''
    ) {
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        $new = clone $this;
        $new->protocolVersion = $version;

        return $new;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader(string $name): bool
    {
        return isset($this->headers[$name]);
    }

    public function getHeader($name): array
    {
        $name = strtolower($name);
        return isset($this->headers[$name]) ? $this->headers[$name] : [];
    }

    public function getHeaderLine(string $name): string
    {
        return implode(',', $this->getHeader($name));
    }

    public function withHeader(string $name, string $value): MessageInterface
    {
        $new = clone $this;
        $new->headers[strtolower($name)] = is_array($value) ? $value : [$value];
        return $new;
    }

    public function withAddedHeader(string $name, string $value): MessageInterface
    {
        $new = clone $this;
        $new->headers[strtolower($name)][] = $value;
        return $new;
    }

    public function withoutHeader(string $name): MessageInterface
    {
        $new = clone $this;
        unset($new->headers[strtolower($name)]);
        return $new;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function withBody(string $body): MessageInterface
    {
        $new = clone $this;
        $new->body = $body;
        return $new;
    }
}