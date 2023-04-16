<?php

namespace SELF\src\Http\Responses;

use App\Domains\Navigation\Controllers\NavigationController;
use SELF\src\Authenticator;
use SELF\src\Helpers\Interfaces\Auth\AuthAppRecordInterface;
use SELF\src\Helpers\Interfaces\Auth\AuthenticatableInterface;
use SELF\src\MustacheTemplating\Mustache;

class MustacheResponse extends Response
{
    protected Mustache $mustache;

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
        if ($authRecord instanceof AuthAppRecordInterface) {
            $user = $authRecord->getUser();
            if ($user instanceof AuthenticatableInterface) {
                $this->data['auth'] = $user->export();
            }

            $navigation = new NavigationController();
            $this->data['navigation_items'] = $navigation->getNavigation();
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