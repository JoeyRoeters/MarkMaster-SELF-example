<?php

namespace SELF\src\Helpers\Interfaces\Auth;

use SELF\src\Helpers\Interfaces\Database\HelixORM\Record\ActiveRecordInterface;

interface AuthAppRecordInterface
{
    public function getUser(): ?ActiveRecordInterface;
}