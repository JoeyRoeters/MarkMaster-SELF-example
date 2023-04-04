<?php

namespace SELF\src\Exceptions\Container;

use SELF\src\Helpers\Interfaces\Container\NotFoundExceptionInterface;

class NotFoundException extends ContainerException implements NotFoundExceptionInterface
{

}