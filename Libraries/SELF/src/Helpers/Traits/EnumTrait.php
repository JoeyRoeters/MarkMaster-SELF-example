<?php

namespace SELF\src\Helpers\Traits;

trait EnumTrait
{
    public function assert(string $value): bool
    {
        return $this->value === $value;
    }
}