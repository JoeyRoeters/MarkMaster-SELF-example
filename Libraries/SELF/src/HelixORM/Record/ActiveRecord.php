<?php

namespace SELF\src\HelixORM\Record;

use Cassandra\Date;
use DateTime;
use SELF\src\Exceptions\HelixORM\HelixException;
use SELF\src\Exceptions\HelixORM\InvalidMethodException;
use SELF\src\Exceptions\HelixORM\QueryBuilderException;
use SELF\src\Exceptions\HelixORM\TableColumnException;
use SELF\src\Exceptions\HelixORM\TableColumnNotFound;
use SELF\src\HelixORM\Helix;
use SELF\src\HelixORM\Query\Criteria\FilterCriteria;
use SELF\src\HelixORM\Query\Criteria\UpdateCriteria;
use SELF\src\HelixORM\Query\QueryBuilder;
use SELF\src\HelixORM\TableColumn;
use SELF\src\Helpers\Enums\HelixORM\Criteria;
use SELF\src\Helpers\Enums\HelixORM\DatabaseMagicFunctionsEnum;
use SELF\src\Helpers\Enums\HelixORM\QueryType;
use SELF\src\Helpers\Interfaces\Database\HelixORM\Record\ActiveRecordInterface;

/**
 * Class ActiveRecord
 *
 * @package SELF\src\HelixORM\Record
 */
abstract class ActiveRecord implements ActiveRecordInterface
{
    protected string $table;

    private bool $isNew = true;

    private array $data = [];

    /** @var TableColumn[] */
    private array $columns = [];

    abstract protected function tableColumns(): array;

    public function __construct()
    {
        $this->columns = $this->tableColumns();

        /** @var TableColumn $column */
        foreach ($this->columns as $column) {
            $this->data[$column->getName()] = $column->getDefaultValue();
        }
    }

    public function __call(string $name, array $arguments)
    {
        // check if method is overriden in child class
        if (method_exists($this, $name)) {
            return $this->{$name}(...$arguments);
        }

        switch (DatabaseMagicFunctionsEnum::identify($name)) {
            case DatabaseMagicFunctionsEnum::GET:
                $columnName = DatabaseMagicFunctionsEnum::getColumn($name);
                $this->checkColumn($columnName);

                return $this->{$columnName};
            case DatabaseMagicFunctionsEnum::SET:
                $columnName = DatabaseMagicFunctionsEnum::getColumn($name);
                $this->checkColumn($columnName);

                if (count($arguments) !== 1) {
                    throw new InvalidMethodException('Method ' . $name . ' expects 1 argument in ' . static::class);
                }

                $this->{$columnName} = $arguments[0];

                return $this;
        }

        throw new InvalidMethodException('Method ' . $name . ' does not exist in ' . static::class);
    }

    /**
     * override get method to return default value if column is not set
     *
     * @param $name
     * @return mixed|null
     * @throws TableColumnNotFound
     */
    public function __get($name) {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

        if ($this->columnExists($name)) {
            return $this->getColumn($name)->getDefaultValue();
        }

        return null;
    }

    /**
     * override set method to set data in data array
     *
     * @param $name
     * @param $value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->isNew;
    }

    /**
     * @param bool $isNew
     */
    public function setIsNew(bool $isNew): static
    {
        $this->isNew = $isNew;

        return $this;
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

    /**
     * check if column exists and throw exception if not
     *
     * @param string $columnName
     * @return void
     * @throws TableColumnNotFound
     */
    protected function checkColumn(string $columnName): void
    {
        $this->getColumn($columnName);
    }

    /**
     * get column by name
     *
     * @param string $columnName
     * @return TableColumn
     * @throws TableColumnNotFound
     */
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

    /**
     * get primary key column
     *
     * @return TableColumn
     * @throws TableColumnException
     */
    private function getPrimaryKey(): TableColumn
    {
        $columns = array_filter($this->columns, function (TableColumn $column) {
            return $column->isPrimaryKey();
        });

        if (count($columns) === 0) {
            throw new TableColumnException('Could not find primary key in table ' . $this->table);
        }

        if (count($columns) > 1) {
            throw new TableColumnException('Found more than one primary key in table ' . $this->table);
        }

        return $columns[array_key_first($columns)];
    }

    /**
     * logic to execute before saving record; return false to cancel save
     *
     * @return bool
     */
    public function preSave(): bool
    {
        return true;
    }

    /**
     * save record
     *
     * @return bool
     * @throws HelixException
     * @throws TableColumnException
     * @throws QueryBuilderException
     */
    public function save(): bool
    {
        if (!$this->preSave()) {
            return false;
        }

        if ($this->columnExists('updated_at')) {
            $this->updated_at = new DateTime();
        }

        // create query builder
        $queryBuilder = new QueryBuilder(
            $this->isNew() ? QueryType::INSERT : QueryType::UPDATE,
            static::class,
        );

        // set criterias for update or insert
        $criterias = [];
        foreach ($this->getColumns() as $column) {
            if ($column->isPrimaryKey()) {
                continue;
            }

            $criterias[] = new UpdateCriteria($column, $this->{$column->getName()});
        }

        // set primary key criteria for update
        if (!$this->isNew()) {
            $primaryKey = $this->getPrimaryKey();
            $criterias[] = new FilterCriteria($primaryKey, Criteria::EQUALS, $this->{$primaryKey->getName()});
        }

        $queryBuilder->setCriterias($criterias);

        // prepare and execute statement
        $statement = Helix::getInstance()->prepare($queryBuilder->getSql());
        $queryBuilder->prepareStatement($statement);

        $execute = $statement->execute();
        if (!$execute) {
            throw new HelixException('Could not execute statement: ' . var_export($statement->errorInfo(), true));
        }

        // set primary key value if new
        if ($this->isNew()) {
            $this->{$this->getPrimaryKey()->getName()} = Helix::getInstance()->lastInsertId();
            $this->setIsNew(false);
        }


        $this->postSave();

        return true;
    }

    /**
     * logic to execute after saving record
     *
     * @return void
     */
    public function postSave(): void
    {
    }

    /**
     * logic to execute before deleting record; return false to cancel delete
     *
     * @return bool
     */
    public function preDelete(): bool
    {
        return true;
    }

    /**
     * delete record
     *
     * @return bool
     * @throws HelixException
     * @throws QueryBuilderException
     * @throws TableColumnException
     */
    public function delete(): bool
    {
        if (!$this->preDelete()) {
            return false;
        }

        if ($this->isNew()) {
            throw new HelixException('Cannot delete new model');
        }

        // create query builder
        $queryBuilder = new QueryBuilder(
            QueryType::DELETE,
            static::class,
        );

        // set primary key criteria for delete
        $queryBuilder->setCriterias([
            new FilterCriteria($this->getPrimaryKey(), Criteria::EQUALS, $this->{$this->getPrimaryKey()->getName()}),
        ]);

        // prepare and execute statement
        $statement = Helix::getInstance()->prepare($queryBuilder->getSql());
        $queryBuilder->prepareStatement($statement);

        $execute = $statement->execute();
        if (!$execute) {
            throw new HelixException('Could not execute statement: ' . var_export($statement->errorInfo(), true));
        }

        $this->postDelete();

        return true;
    }

    /**
     * logic to execute after deleting record
     *
     * @return void
     */
    public function postDelete(): void
    {
    }

    /**
     * fill model from array
     *
     * @param array $data
     * @return $this
     * @throws TableColumnNotFound
     */
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

    /**
     * get model as array
     *
     * @return array
     */
    public function toArray(): array
    {
        $data = [];

        foreach ($this->columns as $column) {
            $data[$column->getName()] = $this->{$column->getName()};
        }

        return $data;
    }

    /**
     * get table name
     *
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * get columns
     *
     * @return array|TableColumn[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }
}