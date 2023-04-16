<?php

namespace App\Helpers\Datatable\Interfaces;

interface DatatableActionDTOInterface
{
    public function getLabel(): string;

    public function getIcon(): string;

    public function getRoute(): string;

    public function export(): array;
}