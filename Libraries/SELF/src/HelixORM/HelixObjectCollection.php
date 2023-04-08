<?php

namespace SELF\src\HelixORM;

use Iterator;
use SELF\src\Helpers\Interfaces\Database\HelixORM\Record\ActiveRecordInterface;

/**
 * Class HelixObjectCollection
 * @package SELF\src\HelixORM
 */
class HelixObjectCollection implements Iterator
{
    /**
     * @var ActiveRecordInterface[]
     */
    private array $objects = [];

    private int $position = 0;

    /**
     * @param ActiveRecordInterface[] $objects
     */
    public function __construct(array $objects = [])
    {
        $this->objects = $objects;
    }

    /**
     * @return ActiveRecordInterface[]
     */
    public function get(): array
    {
        return $this->objects;
    }

    /**
     * @param ActiveRecordInterface[] $objects
     */
    public function set(array $objects): self
    {
        $this->objects = $objects;

        return $this;
    }

    public function add(ActiveRecordInterface $object): self
    {
        $this->objects[] = $object;

        return $this;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->objects);
    }

    /**
     * @return mixed
     */
    public function current(): mixed
    {
        return $this->objects[$this->position];
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * @return void
     */
    public function next(): void
    {
        ++$this->position;
    }

    /**
     * @return void
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->objects[$this->position]);
    }

    /**
     * @return ActiveRecordInterface|null
     */
    public function first(): ?ActiveRecordInterface
    {
        return $this->objects[0] ?? null;
    }
}