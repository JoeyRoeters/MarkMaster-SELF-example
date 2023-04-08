<?php

namespace SELF\src\HelixORM;

use SELF\src\Exceptions\HelixORM\QueryBuilderException;
use SELF\src\HelixORM\Query\Criteria\FilterCriteria;
use SELF\src\HelixORM\Query\Criteria\LimitCriteria;
use SELF\src\HelixORM\Query\Criteria\OrderCriteria;
use SELF\src\Helpers\Interfaces\Database\HelixORM\Query\OperatorCriteriaInterface;

class QueryBuilder
{
    private string $sql = "";

    /** @var array<int, mixed> */
    private array $filterValues = [];

    public function __construct(
        private array $criteria,
        private string $table
    ) {

    }

    /**
     * get the sql for the query
     *
     * @return string
     * @throws QueryBuilderException
     */
    public function getSql(): string
    {
        $this->sql = "SELECT * FROM {$this->table}";

        $this->applyFilters();
        $this->applyOrder();
        $this->applyLimit();

        return $this->sql;
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
                    $this->filterValues[$criteria->getColumn()] = $criteria->getValue();
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
     * returns the values for the filter criteria in the order they were added
     *
     * @return array
     */
    public function getFilterValues(): array
    {
        return $this->filterValues;
    }
}