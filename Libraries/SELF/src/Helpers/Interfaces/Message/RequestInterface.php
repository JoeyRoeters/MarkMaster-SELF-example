<?php

namespace SELF\src\Helpers\Interfaces\Message;

use SELF\src\Helpers\Enums\MethodEnum;

interface RequestInterface extends MessageInterface
{
    public function getMethod(): MethodEnum;
    public function withMethod(MethodEnum $method): RequestInterface;
    public function getUri(): UriInterface;
    public function withUri(string $uri, bool $preserveHost = false): RequestInterface;
    public function getRequestTarget(): string;
    public function withRequestTarget(UriInterface $requestTarget): RequestInterface;
}