<?php

namespace SELF\src\Helpers\Interfaces\Database\HelixORM\Record;

interface ActiveRecordInterface
{
    public function isNew(): bool;

    public function save(): bool;

    public function delete(): bool;

    public function setIsNew(bool $isNew): static;

    public function getTable(): string;

    public function fromArray(array $data): static;

    public function toArray(): array;
}