<?php

namespace SELF\src\Helpers\Interfaces\Database;

interface PdoInterface extends DatabaseInterface
{
    public function getConnection(): \PDO;
}