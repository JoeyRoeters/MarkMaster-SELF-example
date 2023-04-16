<?php

namespace SELF\src\Http\Responses;


use App\Helpers\SweetAlert\SweetAlert;

class RedirectResponse extends Response
{
    public function __construct(string $path)
    {
        header('Location: ' . SweetAlert::appendToUrl($path));

        die();
    }
}