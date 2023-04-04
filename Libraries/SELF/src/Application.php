<?php
namespace SELF\src;

use SELF\src\Database\Database;

class Application extends Container
{
    public function __construct()
    {
        $this->registerServices();
    }

    private function registerServices(): void
    {
        $this->set(Database::class, fn () => Database::getInstance());
    }
}