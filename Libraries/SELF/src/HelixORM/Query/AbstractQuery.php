<?php

namespace SELF\src\HelixORM\Query;


use ReflectionException;
use SELF\src\Exceptions\HelixORM\InvalidMethodException;
use SELF\src\HelixORM\Helix;
use SELF\src\HelixORM\HelixObjectCollection;
use SELF\src\HelixORM\Query\Criteria\EndOrCriteria;
use SELF\src\HelixORM\Query\Criteria\FilterCriteria;
use SELF\src\HelixORM\Query\Criteria\LimitCriteria;
use SELF\src\HelixORM\Query\Criteria\OrCriteria;
use SELF\src\HelixORM\Query\Criteria\OrderCriteria;
use SELF\src\HelixORM\Query\Criteria\QueryCriteria;
use SELF\src\HelixORM\QueryBuilder;
use SELF\src\HelixORM\SqlColumn;
use SELF\src\Helpers\Enums\HelixORM\Criteria;
use SELF\src\Helpers\Enums\HelixORM\DatabaseMagicFunctionsEnum;
use SELF\src\Helpers\Interfaces\Database\HelixORM\Query\CriteriaInterface;
use SELF\src\Helpers\Interfaces\Database\HelixORM\Query\OperatorCriteriaInterface;
use SELF\src\Helpers\Interfaces\Database\HelixORM\Record\ActiveRecordInterface;

/**
 * Class AbstractQuery
 *
 * @package SELF\src\HelixORM\Query
 */
abstract class AbstractQuery
{
    /** @var QueryCriteria[] */
    private array $queryCriterias = [];

    public static function create(): static
    {
        return new static();
    }

    /**
     * retrieve model associated with this query class
     *
     * @return string
     */
    abstract public function getModel(): string;

    /**
     * magic method to handle filter methods
     *
     * @param string $name
     * @param array $arguments
     * @return $this|void
     *
     * @throws InvalidMethodException
     * @throws \SELF\src\Exceptions\HelixORM\ParseException
     */
    public function __call(string $name, array $arguments)
    {
        // loop over magic methods
        switch (DatabaseMagicFunctionsEnum::identify($name)) {
            case DatabaseMagicFunctionsEnum::FILTER:
                $columnName = DatabaseMagicFunctionsEnum::getColumn($name);
                $this->checkColumn($columnName);

                switch (count($arguments)) {
                    case 1:
                        return $this->filterBy($columnName, Criteria::EQUALS, $arguments[0]);
                    case 2:
                        return $this->filterBy($columnName, $arguments[1], $arguments[0]);
                    default:
                        throw new InvalidMethodException('Could not parse filter method. Please use filterByColumnName($value, Criteria $comperison = Criteria::EQUALS)');
                }
            case DatabaseMagicFunctionsEnum::ORDER:
                $columnName = DatabaseMagicFunctionsEnum::getColumn($name);
                $this->checkColumn($columnName);

                switch (count($arguments)) {
                    case 0:
                        return $this->orderBy($columnName, Criteria::ASC);
                    case 1:
                        return $this->orderBy($columnName, $arguments[0]);
                    default:
                        throw new InvalidMethodException('Could not parse order method. Please use orderByColumnName(Criteria $order = Criteria::ASC)');
                }
        }
    }

    /**
     * apply filters to query
     *
     * @param string $columnName
     * @param Criteria $comperision
     * @param $value
     *
     * @return $this
     */
    public function filterBy(string $columnName, Criteria $comperision, $value): static
    {
        $this->queryCriterias[] = new FilterCriteria($columnName, $comperision, $value);

        return $this;
    }

    /**
     * add order to query
     *
     * @param string $columnName
     * @param Criteria $order
     * @return $this
     */
    public function orderBy(string $columnName, Criteria $order = Criteria::ASC): static
    {
        $this->addCriteria(new OrderCriteria($columnName, $order));

        return $this;
    }

    /**
     * add limit to query
     *
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit): static
    {
        $this->addCriteria(new LimitCriteria($limit));

        return $this;
    }

    /**
     * start or criteria
     *
     * @return $this
     */
    public function or(): static
    {
        $this->addCriteria(new OrCriteria());

        return $this;
    }

    /**
     * end or criteria
     *
     * @return $this
     */
    public function endOr(): static
    {
        $this->addCriteria(new EndOrCriteria());

        return $this;
    }

    /**
     * get query criterias
     *
     * @return QueryCriteria[]
     */
    protected function getQueryCriterias(): array
    {
        return $this->queryCriterias;
    }

    /**
     *  add criteria to query
     *
     * @param CriteriaInterface $queryCriteria
     * @return $this
     */
    protected function addCriteria(CriteriaInterface $queryCriteria): self
    {
        // check if criteria already exists
        foreach ($this->queryCriterias as $criteria) {
            if ($criteria->criteriaIdentifier() === $queryCriteria->criteriaIdentifier() && !$criteria instanceof OperatorCriteriaInterface) {
                unset($this->queryCriterias[array_search($criteria, $this->queryCriterias)]);
            }
        }

        // add criteria
        $this->queryCriterias[] = $queryCriteria;

        return $this;
    }

    /**
     *  reset query criterias
     *
     * @return $this
     */
    private function resetCriterias(): static
    {
        $this->queryCriterias = [];

        return $this;
    }

    /**
     * get reflection of model
     *
     * @return \ReflectionClass
     * @throws ReflectionException
     */
    private function getModelReflection(): \ReflectionClass
    {
        return new \ReflectionClass($this->getModel());
    }

    /**
     * check if column exists
     *
     * @param string $columnName
     * @return bool
     * @throws ReflectionException
     */
    protected function columnExists(string $columnName): bool
    {
        $reflection = $this->getModelReflection();

        return $reflection->getMethod('columnExists')->invoke($reflection->newInstance(), $columnName);
    }

    /**
     * execute check if column exists for magic methods
     *
     * @param string $columnName
     * @return void
     * @throws InvalidMethodException
     */
    protected function checkColumn(string $columnName): void
    {
        if (!$this->columnExists($columnName)) {
            throw new InvalidMethodException('Column "' . $columnName . '" does not exist in ' . $this->getModel());
        }
    }

    /**
     * get query builder
     *
     * @return QueryBuilder
     * @throws ReflectionException
     */
    private function getQueryBuilder(): QueryBuilder
    {
        $reflection = $this->getModelReflection();
        return new QueryBuilder(
            $this->getQueryCriterias(),
            $reflection->getProperty('table')->getValue($reflection->newInstanceWithoutConstructor())
        );
    }

    /**
     * get prepared statement for executing
     *
     * @return \PDOStatement
     * @throws ReflectionException
     */
    private function getStatement(): \PDOStatement
    {
        $helix = Helix::getInstance();
        $builder = $this->getQueryBuilder();
        $reflection = $this->getModelReflection();

        $statement = $helix->prepare($builder->getSql());

        $values = $builder->getFilterValues();
        foreach ($values as $key => $value) {
            /** @var SqlColumn $column */
            $column = $reflection->getMethod('getColumn')->invoke($reflection->newInstance(), $key);
            $statement->bindParam($column->getColumnParam() , $value, $column->getType());
        }

        return $statement;
    }

    /**
     * find all models matching the query
     *
     * @return HelixObjectCollection
     * @throws ReflectionException
     */
    public function find(): HelixObjectCollection
    {
        $statement = $this->getStatement();
        $statement->execute();

        $collection = new HelixObjectCollection();

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $model = $this->getModelReflection()->newInstance();
            $model->fromArray($row);

            $collection->add($model);
        }

        $this->resetCriterias();

        return $collection;
    }

    /**
     * find one model matching the query
     *
     * @return ActiveRecordInterface|null
     */
    public function findOne(): ?ActiveRecordInterface
    {
        $this->limit(1);

        $results = $this->find();

        if ($results->count() === 0) {
            return null;
        }

        return $results->first();
    }

    /**
     * count all models matching the query
     *
     * @return int
     */
    public function count(): int
    {
        return $this->find()->count();
    }

    /**
     * check if any models matching the query exist
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->count() > 0;
    }

    /**
     * delete all models matching the query
     *
     * @return int
     */
    public function delete(): int
    {
        $count = 0;

        $results = $this->find();
        foreach ($results as $result) {
            $result->delete();
            $count++;
        }

        return $count;
    }
}