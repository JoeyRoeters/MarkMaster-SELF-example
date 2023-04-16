<?php

namespace App\Helpers\Datatable\DTOs;

use App\Helpers\Datatable\Interfaces\DatatableRowDTOInterface;

class DatatableRowDTO implements DatatableRowDTOInterface
{
    public function __construct(
        private array $data,
        private array $actions,
    ) {
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getActions(): array
    {
        return $this->actions;
    }
}