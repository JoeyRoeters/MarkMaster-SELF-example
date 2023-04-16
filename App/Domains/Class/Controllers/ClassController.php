<?php

namespace App\Domains\Class\Controllers;

use SELF\src\Http\Request;
use SELF\src\Http\Responses\MustacheResponse;
use SELF\src\Http\Responses\RedirectResponse;

class ClassController
{
    public function index(): MustacheResponse
    {
        //todo implement
        return new MustacheResponse('');
    }

    public function indexNewOrEdit(Request $request, array $params): MustacheResponse
    {
        return new MustacheResponse('Classes/new_or_edit', $params);
    }

    public function submitNewOrEdit(Request $request, array $params): RedirectResponse
    {
        return new RedirectResponse('');
    }
}