<?php
namespace SELF\src;

use SELF\src\HelixORM\Helix;

class Application extends Container
{
    public function __construct()
    {
        $this->registerServices();
    }

    private function registerServices(): void
    {
        $this->set(Helix::class, fn () => Helix::getInstance());
    }
}