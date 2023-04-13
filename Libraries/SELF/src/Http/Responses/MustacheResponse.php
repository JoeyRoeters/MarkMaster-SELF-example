<?php

namespace SELF\src\Http\Responses;

use SELF\src\MustacheTemplating\Mustache;

class MustacheResponse extends Response
{
    private Mustache $mustache;

    public function __construct(
        private string $template,
        private array $data = []
    )
    {
        $this->mustache = new Mustache($this->template, $this->data);

        parent::__construct();
    }

    public function output(): void
    {
        $this->body = $this->mustache->render();

        parent::output();
    }
}