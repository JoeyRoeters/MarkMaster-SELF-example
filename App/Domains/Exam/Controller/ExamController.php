<?php

namespace App\Domains\Exam\Controller;

use App\Traits\UserTrait;
use SELF\src\Http\Controller;
use SELF\src\Http\Request;

class ExamController extends Controller
{
    use UserTrait;

    public function index()
    {
        var_dump($this->user);
    }

    public function store(Request $request, array $params)
    {
        var_dump($this->user);
    }

    public function show(Request $request, array $params)
    {
        var_dump($this->user);
    }

    public function delete(Request $request, array $params)
    {
        var_dump($this->user);
    }
}