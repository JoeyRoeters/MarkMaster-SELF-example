<?php

namespace SELF\src\Auth;

use SELF\src\HelixORM\Record\ActiveRecord;
use SELF\src\Helpers\Interfaces\Auth\AuthenticatableInterface;

abstract class AuthenticatableRecord extends ActiveRecord implements AuthenticatableInterface
{
}