<?php

namespace App\Helpers\Datatable\DTOs;

use App\Helpers\Datatable\Interfaces\DatatableHeaderDTOInterface;

class DatatableHeaderDTO implements DatatableHeaderDTOInterface
{
    public function __construct(
        private string $title,
    ) {
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}