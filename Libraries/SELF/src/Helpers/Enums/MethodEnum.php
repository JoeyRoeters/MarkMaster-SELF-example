<?php

namespace SELF\src\Helpers\Enums;

use SELF\src\Helpers\Traits\EnumTrait;

enum MethodEnum: string
{
    use EnumTrait;

    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case DELETE = 'DELETE';
    case PATCH = 'PATCH';
    case OPTIONS = 'OPTIONS';
    case HEAD = 'HEAD';
}
