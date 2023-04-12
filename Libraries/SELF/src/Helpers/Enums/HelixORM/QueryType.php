<?php

namespace SELF\src\Helpers\Enums\HelixORM;

enum QueryType
{
    case SELECT;
    case INSERT;
    case UPDATE;
    case DELETE;

    public function sql(string $table): string
    {
        return match ($this) {
            self::SELECT => "SELECT * FROM {$table}",
            self::INSERT => "INSERT INTO {$table}",
            self::UPDATE => "UPDATE {$table}",
            self::DELETE => "DELETE FROM {$table}",
        };
    }

    public function hasFilters(): bool
    {
        return match ($this) {
            self::SELECT, self::UPDATE, self::DELETE => true,
            default => false,
        };
    }

    public function hasOrder(): bool
    {
        return match ($this) {
            self::SELECT => true,
            default => false,
        };
    }

    public function hasLimit(): bool
    {
        return match ($this) {
            self::SELECT => true,
            default => false,
        };
    }

    public function hasOffset(): bool
    {
        return match ($this) {
            self::SELECT => true,
            default => false,
        };
    }
}
