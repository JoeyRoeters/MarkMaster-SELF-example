<?php

namespace App\Helpers\Datatable\Interfaces;

interface DatatableRowDTOInterface
{
    public function getData(): array;

    /**
     * @return DatatableActionDTOInterface[]
     */
    public function getActions(): array;
}