<?php

namespace App\Helpers\Datatable\DTOs;

use App\Helpers\Datatable\Interfaces\DatatableActionDTOInterface;

class DatatableActionDTO implements DatatableActionDTOInterface
{
    public function __construct(
        private string $label,
        private string $icon,
        private string $route,
    ) {
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function export(): array
    {
        return [
            'label' => $this->getLabel(),
            'icon' => $this->getIcon(),
            'route' => $this->getRoute(),
        ];
    }
}