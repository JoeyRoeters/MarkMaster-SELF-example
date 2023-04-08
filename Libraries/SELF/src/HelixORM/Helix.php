<?php

namespace SELF\src\HelixORM;

use PDO;
use SELF\src\Helpers\Interfaces\Database\PdoInterface;

/**
 * Class Helix is the ORM for the SELF project. It is a wrapper around PDO.
 */
class Helix implements PdoInterface
{
    private static $instance;

    private PDO $connection;

    private function __construct(
        private string $database,
        private string $host,
        private string $username,
        private string $password,
        private string $charset = 'utf8mb4',
        private string $collation = 'utf8mb4_unicode_ci',
        private string $driver = 'mysql',
        private string $port = '3306'
    ) {
        $this->connection = new \PDO(
            sprintf('%s:host=%s;port=%s;dbname=%s;charset=%s', $this->driver, $this->host, $this->port, $this->database, $this->charset),
            $this->username,
            $this->password
        );
    }

    /**
     * @return static
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self(
                environment('DB_DATABASE'),
                environment('DB_HOST'),
                environment('DB_USERNAME'),
                environment('DB_PASSWORD')
            );
        }

        return self::$instance;
    }

    /**
     * @return PDO
     */
    public function getConnection(): \PDO
    {
        return $this->connection;
    }

    /**
     * create a new prepared PDOStatement object
     *
     * @param string $statement
     * @param array $driverOptions
     *
     * @return \PDOStatement
     */
    public function prepare(string $statement, array $driverOptions = []): \PDOStatement
    {
        return $this->getConnection()->prepare($statement, $driverOptions);
    }

    /**
     * fetch all rows from a prepared PDOStatement object
     *
     * @param string $statement
     * @param array $driverOptions
     * @return array
     */
    public function fetchAll(string $statement, array $driverOptions = []): array
    {
        return $this->prepare($statement, $driverOptions)->fetchAll();
    }

    /**
     * fetch a single row from a prepared PDOStatement object
     *
     * @param string $statement
     * @param array $driverOptions
     * @return array
     */
    public function fetch(string $statement, array $driverOptions = []): array
    {
        return $this->prepare($statement, $driverOptions)->fetch();
    }

    /**
     * execute a prepared PDOStatement object
     *
     * @param string $statement
     * @param array $driverOptions
     * @return bool
     */
    public function execute(string $statement, array $driverOptions = []): bool
    {
        return $this->prepare($statement, $driverOptions)->execute();
    }

    /**
     * retrieve the ID of the last inserted row or sequence value
     *
     * @return string
     */
    public function lastInsertId(): string
    {
        return $this->getConnection()->lastInsertId();
    }

    /**
     * initiate a transaction
     *
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->getConnection()->beginTransaction();
    }

    /**
     * commit a transaction
     *
     * @return bool
     */
    public function commit(): bool
    {
        return $this->getConnection()->commit();
    }

    /**
     * roll back a transaction
     *
     * @return bool
     */
    public function rollBack(): bool
    {
        return $this->getConnection()->rollBack();
    }

    /**
     * check if inside a transaction
     *
     * @return bool
     */
    public function inTransaction(): bool
    {
        return $this->getConnection()->inTransaction();
    }

    /**
     * get an attribute
     *
     * @param int $attribute
     * @return mixed
     */
    public function getAttribute(int $attribute)
    {
        return $this->getConnection()->getAttribute($attribute);
    }

    /**
     * set an attribute
     *
     * @param int $attribute
     * @param mixed $value
     * @return bool
     */
    public function setAttribute(int $attribute, mixed $value): bool
    {
        return $this->getConnection()->setAttribute($attribute, $value);
    }

    /**
     * retrieve the error code associated with the last operation on the database handle
     *
     * @return string
     */
    public function errorCode(): string
    {
        return $this->getConnection()->errorCode();
    }

    /**
     * retrieve extended error information associated with the last operation on the database handle
     *
     * @return array
     */
    public function errorInfo(): array
    {
        return $this->getConnection()->errorInfo();
    }

    /**
     * quote a string for use in a query
     *
     * @param string $string
     * @param int $parameterType
     * @return string
     */
    public function quote(string $string, int $parameterType = \PDO::PARAM_STR): string
    {
        return $this->getConnection()->quote($string, $parameterType);
    }

    /**
     * execute an SQL statement and return the number of affected rows
     *
     * @param string $statement
     * @return int
     */
    public function exec(string $statement): int
    {
        return $this->getConnection()->exec($statement);
    }

    /**
     * execute an SQL statement, returning a result set as a PDOStatement object
     *
     * @param string $statement
     * @return \PDOStatement
     */
    public function query(string $statement): \PDOStatement
    {
        return $this->getConnection()->query($statement);
    }
}