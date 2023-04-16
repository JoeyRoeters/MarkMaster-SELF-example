<?php

namespace App\Domains\Exam\Controller;

use App\Domains\Exam\Repository\ExamQuery;
use App\Helpers\Datatable\DTOs\DatatableHeaderDTO;
use App\Helpers\Datatable\DTOs\DatatableRowDTO;
use App\Responses\DatatableResponse;
use App\Traits\UserTrait;
use SELF\src\Http\Controller;
use SELF\src\Http\Request;
use SELF\src\Http\Responses\MustacheResponse;

class ExamController extends Controller
{
    use UserTrait;

    public function index()
    {
        $datatable = new DatatableResponse('Tentamens');

        $headers = [
            new DatatableHeaderDTO('Examen'),
            new DatatableHeaderDTO('Datum'),
        ];

        $datatable->setHeaders($headers);

        $rows = array_map(function ($exam) {
            return new DatatableRowDTO($exam->export(), []);
        }, ExamQuery::create()->filterByIsVisible($this->user)->find()->getObjects());

        $datatable->setRows($rows);


        return $datatable;
    }

    public function indexNewOrEdit(Request $request, array $params)
    {
        return new MustacheResponse();
    }

    public function submitNewOrEdit(Request $request, array $params)
    {
        var_dump($this->user);
    }
}