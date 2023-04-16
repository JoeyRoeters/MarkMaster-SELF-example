<?php

namespace SELF\src\Http\Responses;

class RedirectResponse extends Response
{
    public function __construct(string $path)
    {
        header('Location: ' . $path);
        die();
    }
}