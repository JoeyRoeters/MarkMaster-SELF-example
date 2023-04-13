<?php
namespace App\Domains\User\Controllers;

use SELF\src\Http\Request;
use SELF\src\Http\Responses\MustacheResponse;
use SELF\src\Http\Responses\Response;

class Overview
{
    public function __construct()
    {
    }

    public function index(Request $request, array $params)
    {
        $data = ['name' => 'appel'];
        $data['hobbies'] = ['gaming', 'coding', 'reading'];
        return new MustacheResponse('test', $data);
    }

    public function test(Request $request)
    {
        var_dump('this is root');
    }
}