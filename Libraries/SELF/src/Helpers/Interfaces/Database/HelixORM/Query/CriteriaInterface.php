<?php

namespace SELF\src\Helpers\Interfaces\Database\HelixORM\Query;

interface CriteriaInterface
{
    /**
     * @return string
     */
    public function criteriaIdentifier(): string;

    public function getSql(): string;
}