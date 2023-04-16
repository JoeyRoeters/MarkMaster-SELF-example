<?php
namespace SELF\src\Http\Responses;
use SELF\src\Helpers\Interfaces\Message\ResponseInterface;
use SELF\src\Http\Message;

class Response extends Message implements ResponseInterface
{
    protected int $statusCode = 200;
    protected string $reasonPhrase = '';
    protected string $body;

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

    public function output(): void
    {
        http_response_code($this->statusCode);

        echo $this->body;
    }
}