<?php

namespace App\Helpers\Datatable\Interfaces;

interface DatatableDTOInterface
{
    /**
     * @return DatatableHeaderDTOInterface[]
     */
    public function getColumns(): array;

    /**
     * @return DatatableRowDTOInterface[]
     */
    public function getRows(): array;

    /**
     * @return DatatableActionDTOInterface[]
     */
    public function getActions(): array;
}