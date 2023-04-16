<?php

namespace App\Helpers\Datatable\DTOs;

use App\Helpers\Datatable\Interfaces\DatatableRowDTOInterface;

class DatatableRowDTO implements DatatableRowDTOInterface
{
    public function __construct(
        private array $data,
    ) {
    }

    public function getData(): array
    {
        return $this->data;
    }
}