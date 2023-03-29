<?php

namespace SELF\src\Exceptions;

use SELF\src\Helpers\Interfaces\Container\NotFoundExceptionInterface;

class NotFoundException extends ContainerException implements NotFoundExceptionInterface
{

}