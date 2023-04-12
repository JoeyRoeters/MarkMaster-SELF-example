<?php

namespace SELF\src\HelixORM\Query;

use SELF\src\Exceptions\HelixORM\QueryBuilderException;
use SELF\src\HelixORM\Query\Criteria\FilterCriteria;
use SELF\src\HelixORM\Query\Criteria\LimitCriteria;
use SELF\src\HelixORM\Query\Criteria\OrderCriteria;
use SELF\src\HelixORM\Query\Criteria\UpdateCriteria;
use SELF\src\Helpers\Enums\HelixORM\QueryType;
use SELF\src\Helpers\Interfaces\Database\HelixORM\Query\CriteriaInterface;
use SELF\src\Helpers\Interfaces\Database\HelixORM\Query\OperatorCriteriaInterface;

/**
 * Class QueryBuilder
 * @package SELF\src\HelixORM\Query
 */
class QueryBuilder
{
    public static int $countBinds = 1;

    private string $sql = "";

    /** @var UpdateCriteria[] */
    private array $updates = [];

    /** @var FilterCriteria[] */
    private array $filters = [];

    /** @var CriteriaInterface[] */
    private array $criteria = [];

    public function __construct(
        private QueryType $queryType,
        private string $recordClass,
    ) {
        self::$countBinds = 1;
    }

    /**
     * get the reflection of the record
     *
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    private function getRecordReflection(): \ReflectionClass
    {
        return new \ReflectionClass($this->recordClass);
    }

    /**
     * get the sql for the query
     *
     * @return string
     * @throws QueryBuilderException
     */
    public function getSql(): string
    {
        $reflection = $this->getRecordReflection();
        $table = $reflection->getProperty('table')->getValue($reflection->newInstanceWithoutConstructor());

        $this->sql = $this->queryType->sql($table);

        if ($this->queryType === QueryType::INSERT) {
            $this->applyInsert();
        }

        if ($this->queryType === QueryType::UPDATE) {
            $this->applyUpdates();
        }

        if ($this->queryType->hasFilters()) {
            $this->applyFilters();
        }

        if ($this->queryType->hasOrder()) {
            $this->applyOrder();
        }

        if ($this->queryType->hasLimit()) {
            $this->applyLimit();
        }

        return $this->sql;
    }

    public function setCriterias(array $criteria): void
    {
        $this->criteria = $criteria;
    }

    /**
     * apply insert if it exists
     *
     * @return void
     * @throws QueryBuilderException
     */
    private function applyInsert(): void
    {
        /** @var UpdateCriteria[] $updateCriteria */
        $updateCriteria = array_filter($this->criteria, function ($criteria) {
            return $criteria instanceof UpdateCriteria;
        });

        if (count($updateCriteria) < 1) {
            throw new QueryBuilderException("No updates found");
        }

        $columns = array_map(function ($criteria) {
            return $criteria->getTableColumn()->getName();
        }, $updateCriteria);
        $values = array_map(function () {
            return "?";
        }, $updateCriteria);

        $this->sql .= " (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $values) . ")";

        $this->updates = $updateCriteria;
    }

    /**
     * apply updates if they exist
     *
     * @return void
     * @throws QueryBuilderException
     */
    private function applyUpdates(): void
    {
        /** @var UpdateCriteria[] $updateCriteria */
        $updateCriteria = array_filter($this->criteria, function ($criteria) {
            return $criteria instanceof UpdateCriteria;
        });

        if (count($updateCriteria) < 1) {
            throw new QueryBuilderException("No updates found");
        }

        $this->sql .= " SET ";

        foreach ($updateCriteria as $criteria) {
            $this->sql .= $criteria->getSql() . ", ";

            $this->updates[] = $criteria;
        }
        $this->sql = rtrim($this->sql, ", ");
    }

    /**
     * apply filters if they exist
     *
     * @return void
     */
    private function applyFilters(): void
    {
        /** @var FilterCriteria[] $filterCriteria */
        $filterCriteria = array_filter($this->criteria, function ($criteria) {
            return $criteria instanceof FilterCriteria || $criteria instanceof OperatorCriteriaInterface;
        });
        if (count($filterCriteria) > 0) {
            $count = 0;
            $operator = " AND ";
            $this->sql .= " WHERE ";
            foreach ($filterCriteria as $criteria) {
                // if we have an operator criteria, we need to set the operator and continue
                if ($count > 0) {
                    if ($criteria instanceof OperatorCriteriaInterface) {
                        $operator = $criteria->getSql();

                        continue;
                    } else {
                        $this->sql .= $operator;
                    }
                }

                // append the criteria sql
                $this->sql .= $criteria->getSql();

                // if we have a filter criteria, we need to add the value to the filter inserts
                if ($criteria instanceof FilterCriteria) {
                    $this->filters[] = $criteria;
                }

                $count++;
            }
        }
    }

    /**
     * apply order criteria if it exists
     *
     * @return void
     */
    private function applyOrder(): void
    {
        /** @var OrderCriteria[] $filterCriteria */
        $orderCriteria = array_filter($this->criteria, function ($criteria) {
            return $criteria instanceof OrderCriteria;
        });

        if (count($orderCriteria) > 0) {
            $this->sql .= " ORDER BY ";
            $count = 0;
            foreach ($orderCriteria as $criteria) {
                if ($count > 0) {
                    $this->sql .= ", ";
                }

                $this->sql .= $criteria->getSql();

                $count++;
            }
        }
    }

    /**
     * apply limit criteria if it exists
     *
     * @return void
     * @throws QueryBuilderException
     */
    private function applyLimit(): void
    {
        /** @var LimitCriteria[] $filterCriteria */
        $limitCriteria = array_filter($this->criteria, function ($criteria) {
            return $criteria instanceof LimitCriteria;
        });

        if (count($limitCriteria) > 1) {
            throw new QueryBuilderException("Only one limit criteria is allowed");
        }

        if (count($limitCriteria) === 1) {
            $this->sql .= ' ' . $limitCriteria[array_key_first($limitCriteria)]->getSql();
        }
    }

    /**
     * prepare the statement with the values
     *
     * @param \PDOStatement $statement
     * @return void
     */
    public function prepareStatement(\PDOStatement &$statement): void
    {
        foreach ($this->getUpdates() as $update) {
            $update->bindValue($statement);
        }

        foreach ($this->getFilters() as $filter) {
            $filter->bindValue($statement);
        }
    }

    /**
     * returns the values for the filter criteria in the order they were added
     *
     * @return FilterCriteria[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * returns the values for the update criteria in the order they were added
     *
     * @return UpdateCriteria[]
     */
    public function getUpdates(): array
    {
        return $this->updates;
    }
}