<?php

namespace App\Domains\Authentication\Controllers;

use SELF\src\Http\Controller;
use SELF\src\Http\Request;
use SELF\src\Http\Responses\MustacheResponse;

class AuthenticationController extends Controller
{
    public function index(Request $request, array $params)
    {
        return new MustacheResponse('Authentication/login', ['login' => false]);
    }
}