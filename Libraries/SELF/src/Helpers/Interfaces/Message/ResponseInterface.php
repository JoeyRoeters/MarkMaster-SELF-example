<?php

namespace SELF\src\Helpers\Interfaces\Message;

interface ResponseInterface extends MessageInterface
{
    public function getStatusCode(): int;
    public function withStatus($code, $reasonPhrase = ''): ResponseInterface;
    public function getReasonPhrase(): string;
}