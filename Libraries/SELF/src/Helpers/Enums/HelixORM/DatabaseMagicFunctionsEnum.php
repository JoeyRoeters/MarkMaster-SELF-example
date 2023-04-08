<?php

namespace SELF\src\Helpers\Enums\HelixORM;

use SELF\src\Exceptions\HelixORM\ParseException;

/**
 * Enum DatabaseMagicFunctionsEnum
 *
 * @package SELF\src\Helpers\Enums\HelixORM
 */
enum DatabaseMagicFunctionsEnum: string
{
    case FILTER = 'filter';
    case ORDER = 'order';

    /**
     * Identify the type of magic function
     *
     * @param string $name
     *
     * @return static|null
     */
    public static function identify(string $name): ?self
    {
        $name = strtolower($name);

        if (strpos($name,self::FILTER->value) === 0) {
            return self::FILTER;
        }

        if (strpos($name,self::ORDER->value) === 0) {
            return self::ORDER;
        }

        return null;
    }

    /**
     * Get the column name from the magic function
     *
     * @param string $name
     * @return string
     * @throws ParseException
     */
    public static function getColumn(string $name): string
    {
        $type = self::identify($name);

        switch ($type) {
            case self::FILTER:
            case self::ORDER:
                // remove filterBy from name
                $name = substr($name, strlen($type->value) + 2);

                break;
            default:
                throw new ParseException('Could not parse column name from method name');
        }

        // split name by capital letters
        $name = preg_split('/(?=[A-Z])/', $name);

        // convert to lowercase and remove empty values
        $name = array_map('strtolower', $name);
        $name = array_filter($name, fn($value) => $value !== '');

        // if there are more than 1 value, implode them with an underscore
        if (count($name) > 1) {
            return implode('_', $name);
        }

        return $name[array_key_first($name)];
    }
}
