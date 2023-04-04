<?php
namespace SELF\src\Http;
use SELF\src\Helpers\Interfaces\Message\ResponseInterface;

class Response extends Message implements ResponseInterface
{
    protected $statusCode = 200;
    protected $reasonPhrase = '';
    protected $body;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function withStatus($code, $reasonPhrase = ''): self
    {
        $new = clone $this;
        $new->statusCode = $code;
        $new->reasonPhrase = $reasonPhrase;
        return $new;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function withBody(string $body): self
    {
        $new = clone $this;
        $new->body = $body;

        return $new;
    }
}