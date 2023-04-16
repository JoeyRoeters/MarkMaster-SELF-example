<?php

namespace SELF\src;

use SELF\src\Exceptions\Validation\ValidationMethodNotFoundException;
use SELF\src\Helpers\Enums\Validation\ValidateEnum;

class Validator
{
    public static function validate(ValidateEnum $validateEnum, string | array $data): bool
    {
        if ($validateEnum === ValidateEnum::NOT_EMPTY) {
            return ! empty($data);
        }

        throw new ValidationMethodNotFoundException();
    }
}