<?php

namespace SELF\src\HelixORM;

use SELF\src\Helpers\Enums\HelixORM\ColumnType;

class TableColumn
{
    private string $relation;

    private bool $autoTimestamp = false;
    private bool $isPrimaryKey = false;

    public function __construct(
        private string $name,
        private ColumnType $type,
        private mixed $defaultValue = null,
        private bool $nullable = true,
    )
    {
    }

    public static function create(string $name, ColumnType $type = ColumnType::STRING, mixed $defaultValue = null, bool $nullable = true): self
    {
        return new static($name, $type, $defaultValue, $nullable);
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

    /**
     * @return mixed
     */
    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    /**
     * @param mixed $defaultValue
     */
    public function setDefaultValue(mixed $defaultValue): void
    {
        $this->defaultValue = $defaultValue;
    }

    public function getRelation(): string
    {
        return $this->relation;
    }

    public function autoTimestamp(): self
    {
        $this->setDefaultValue(new \DateTime());
        $this->autoTimestamp = true;

        return $this;
    }

    public function isPrimaryKey(): bool
    {
        return $this->isPrimaryKey;
    }

    public function setPrimaryKey(bool $isPrimaryKey): self
    {
        $this->isPrimaryKey = $isPrimaryKey;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAutoTimestamp(): bool
    {
        return $this->autoTimestamp;
    }

    public function setRelation(string $relation): self
    {
        $this->relation = $relation;

        return $this;
    }
}