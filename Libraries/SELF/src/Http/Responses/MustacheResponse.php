<?php

namespace SELF\src\Http\Responses;

use SELF\src\Authenticator;
use SELF\src\Helpers\Interfaces\Auth\AuthenticatableInterface;
use SELF\src\MustacheTemplating\Mustache;

class MustacheResponse extends Response
{
    private Mustache $mustache;

    public function __construct(
        private string $template,
        private array $data = [],
        private ?string $title = null,
    )
    {
        if ($this->title) {
            $this->data['title'] = $this->title;
        }

        $authRecord = Authenticator::getInstance()->getAuthRecordFromSession();
        if ($authRecord instanceof AuthenticatableInterface) {
            $this->data['auth'] = $authRecord->export();
        }

        $this->mustache = new Mustache($this->template, $this->data);

        parent::__construct();
    }

    public function output(): void
    {
        $this->body = $this->mustache->render();

        parent::output();
    }
}