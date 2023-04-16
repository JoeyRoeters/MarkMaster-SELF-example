<?php

namespace App\Domains\Mark\Controllers;

use App\Responses\DatatableResponse;
use SELF\src\Http\Controller;

class MarkController extends Controller
{
    public function index()
    {
        $dataTable = new DataTableResponse('Tentamens');
    }
}