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

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @return array
     */
    public function export(): array
    {
        return [
            'label' => $this->getLabel(),
            'icon' => $this->getIcon(),
            'route' => $this->getRoute(),
        ];
    }
}