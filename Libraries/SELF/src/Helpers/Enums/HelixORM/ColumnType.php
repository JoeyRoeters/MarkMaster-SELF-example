<?php

namespace SELF\src\Helpers\Enums\HelixORM;

use DateTime;
use PDO;

/**
 * Class ColumnType
 * @package SELF\src\Helpers\Enums\HelixORM
 */
enum ColumnType
{
    case INT;
    case STRING;
    case DATETIME;
    case FLOAT;
    case BOOL;
    case JSON;

    /**
     * cast value to column type
     *
     * @param mixed $value
     * @return mixed
     */
    public function cast(mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($this) {
            self::INT => (int) $value,
            self::STRING => (string) $value,
            self::DATETIME => !empty($value) ? DateTime::createFromFormat('Y-m-d H:i:s', $value) : null,
            self::FLOAT => (float) $value,
            self::BOOL => (bool) $value,
            self::JSON => json_decode($value ?? '', true)
        };
    }

    /**
     * @return int
     */
    public function getPdoType(?Criteria $criteria = null): int
    {
        if ($criteria instanceof Criteria) {
            if ($criteria->isLike()) {
                return PDO::PARAM_STR;
            }

            if ($criteria->isNull()) {
                return PDO::PARAM_NULL;
            }
        }

        return match ($this) {
            self::INT => PDO::PARAM_INT,
            self::STRING => PDO::PARAM_STR,
            self::DATETIME => PDO::PARAM_STR,
            self::FLOAT => PDO::PARAM_STR,
            self::BOOL => PDO::PARAM_BOOL,
            self::JSON => PDO::PARAM_STR
        };
    }
}
