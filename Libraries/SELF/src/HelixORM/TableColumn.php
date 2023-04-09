<?php

namespace SELF\src\HelixORM;

use SELF\src\Helpers\Enums\HelixORM\ColumnType;

class TableColumn
{
    private string $relation;

    public function __construct(
        private string $name,
        private ColumnType $type,
        private bool $nullable = true,
    )
    {
    }

    public static function create(string $name, ColumnType $type = ColumnType::STRING, bool $nullable = true): self
    {
        return new static($name, $type, $nullable);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): ColumnType
    {
        return $this->type;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function getRelation(): string
    {
        return $this->relation;
    }

    public function setRelation(string $relation): self
    {
        $this->relation = $relation;

        return $this;
    }

    public function getColumnParam(): string
    {
        return $this->getName() . '_param';
    }
}