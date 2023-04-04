<?php

namespace SELF\src\Database;

use SELF\src\Helpers\Interfaces\Database\PdoInterface;

class Database implements PdoInterface
{
    private static $instance;

    private \PDO $connection;

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

        var_dump($this->connection->query('SHOW TABLES')->fetchAll());
        die('a');
    }

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

    public function getConnection(): \PDO
    {
        return $this->connection;
    }
}