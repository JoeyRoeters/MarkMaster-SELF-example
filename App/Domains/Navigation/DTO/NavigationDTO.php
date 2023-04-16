<?php

namespace App\Domains\Navigation\DTO;

class NavigationDTO
{
    public function __construct(
        public string $name,
        public string $url,
        public string $icon,
        public bool $isSubMenu = false
    )
    {
    }

    /**
     * @return array
     */
    public function export(): array
    {
        return [
            'name' => $this->name,
            'url' => environment('APP_URL') . $this->url,
            'icon' => $this->icon,
            'isSubMenu' => $this->isSubMenu
        ];
    }
}