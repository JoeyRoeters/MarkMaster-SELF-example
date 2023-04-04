<?php

namespace SELF\src\Helpers\Interfaces\Message;


interface MessageInterface
{
    public function getProtocolVersion(): string;
    public function withProtocolVersion(string $version): MessageInterface;
    public function getHeaders(): array;
    public function hasHeader(string $name): bool;
    public function getHeader(string $name): array;
    public function getHeaderLine(string $name): string;
    public function withHeader(string $name, string $value): MessageInterface;
    public function withAddedHeader(string $name, string $value): MessageInterface;
    public function withoutHeader(string $name): MessageInterface;
    public function getBody(): string;
    public function withBody(string $body): MessageInterface;
}