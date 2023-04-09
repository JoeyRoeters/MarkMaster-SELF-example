<?php

namespace SELF\src\HelixORM\Record;

use SELF\src\Exceptions\HelixORM\TableColumnNotFound;
use SELF\src\HelixORM\TableColumn;
use SELF\src\Helpers\Interfaces\Database\HelixORM\Record\ActiveRecordInterface;

abstract class ActiveRecord implements ActiveRecordInterface
{
    protected string $table;

    private array $data = [];

    /** @var TableColumn[] */
    private array $columns = [];

    abstract protected function tableColumns(): array;

    public function __construct()
    {
        $this->columns = $this->tableColumns();
    }

    public function __call(string $name, array $arguments)
    {
        var_dump($name, $arguments);
        die();
    }

    public function __get($name) {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
    }

    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    /**
     * check if column exists
     *
     * @param string $column
     * @return bool
     */
    protected function columnExists(string $column): bool
    {
        $columns = array_map(function (TableColumn $column) {
            return $column->getName();
        }, $this->columns);

        return in_array($column, $columns);
    }

    protected function getColumn(string $columnName): TableColumn
    {
        $columns = array_filter($this->columns, function (TableColumn $column) use ($columnName) {
            return $column->getName() === $columnName;
        });

        if (count($columns) === 0) {
            throw new TableColumnNotFound('Could not find column ' . $columnName . ' in table ' . $this->table);
        }

        return $columns[array_key_first($columns)];
    }

    public function fromArray(array $data): static
    {
        foreach ($data as $key => $value) {
            if ($this->columnExists($key)) {
                $column = $this->getColumn($key);

                $this->{$key} = $column->getType()->cast($value);
            }
        }

        return $this;
    }
}