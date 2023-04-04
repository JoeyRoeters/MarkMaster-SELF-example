<?php
namespace SELF\src\Http;

use SELF\src\Application;

class Kernel
{
    public function __construct(
        private Application $app,
        private Router $router,
    ) {
    }


}