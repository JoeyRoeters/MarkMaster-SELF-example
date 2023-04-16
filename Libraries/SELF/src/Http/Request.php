<?php
namespace SELF\src\Http;

use SELF\src\Exceptions\Validation\ValidationMethodNotFoundException;
use SELF\src\Helpers\Enums\MethodEnum;
use SELF\src\Helpers\Enums\Validation\ValidateEnum;
use SELF\src\Helpers\Interfaces\Message\RequestInterface;
use SELF\src\Helpers\Interfaces\Message\UriInterface;
use SELF\src\Http\Responses\RedirectResponse;
use SELF\src\Validator;

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

    /**
     * @param array<string, ValidateEnum> $toValidate
     * @return bool|array
     * @throws ValidationMethodNotFoundException
     */
    public function validate(array $toValidate): bool | array
    {
        $data = $this->allParameters();

        foreach ($toValidate as $key => $validateEnum) {
            if (! isset($data[$key]) || ! Validator::validate($validateEnum, $data[$key])) {
                return false;
            }
        }

        return $data;
    }

    public function back(): RedirectResponse
    {
        return new RedirectResponse($_SERVER['HTTP_REFERER']);
    }

    public function allParameters(): array
    {
        return $this->getParameters() + $this->postParameters();
    }

    public function postParameters(): array
    {
        return $_POST;
    }

    public function getParameters(): array
    {
        return $_GET;
    }
}