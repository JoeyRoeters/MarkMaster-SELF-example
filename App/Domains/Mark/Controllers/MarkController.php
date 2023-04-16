<?php

namespace App\Domains\Mark\Controllers;

use App\Authenticator;
use App\Domains\Mark\Repository\MarkQuery;
use App\Helpers\Datatable\DTOs\DatatableHeaderDTO;
use App\Helpers\Datatable\DTOs\DatatableRowDTO;
use App\Responses\DatatableResponse;
use SELF\src\Http\Controller;

class MarkController extends Controller
{
    public function index()
    {
        $dataTable = new DataTableResponse('Cijfers');

        $headers = [
            new DatatableHeaderDTO('Tentamen'),
            new DatatableHeaderDTO('Cijfer'),
            new DatatableHeaderDTO('Datum'),
        ];

        $dataTable->setHeaders($headers);

        $rows = array_map(function ($mark) {
            return new DatatableRowDTO(
                [
                    $mark->getExam()->getName(),
                    $mark->mark / 10,
                    $mark->created_at->format('d-m-Y'),
                ]
            );
        }, MarkQuery::create()->filterByStudentId(Authenticator::user()->getId())->find()->getObjects());

        $dataTable->setRows($rows);


        return $dataTable;
    }
}