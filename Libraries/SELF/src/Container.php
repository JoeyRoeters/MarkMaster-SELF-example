<?php

namespace SELF\src;

use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;
use SELF\src\Exceptions\ContainerException;
use SELF\src\Helpers\Interfaces\Container\ContainerInterface;
use Closure;

class Container implements ContainerInterface
{
    private static $instance;

    /**
     * @var Closure[] $register
     */
    protected array $register = [];

    public static function getInstance(): static
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    public function get(string $id): mixed
    {
        if ($this->has($id)) {
            $entry = $this->register[$id];
            return $entry($this);
        }

        return $this->resolve($id);
    }

    public function has(string $id): bool
    {
        return isset($this->register[$id]);
    }

    public function set(string $id, callable $concrete): void
    {
        $this->register[$id] = $concrete;
    }

    public function resolve(string $id)
    {
        $reflectionClass = new ReflectionClass($id);
        if (! $reflectionClass->isInstantiable()) {
            throw new ContainerException("Class $id is not instantiable.");
        }

        $constructor = $reflectionClass->getConstructor();
        if (! $constructor) {
            return new $id();
        }

        $parameters = $constructor->getParameters();
        if (! $parameters) {
            return new $id();
        }

        $dependencies = array_map(function (ReflectionParameter $param) use ($id) {
            $name = $param->getName();
            $type = $param->getType();

            if (! $type) {
                throw new ContainerException("Failed to resolve class $id because param $name is missing type hint.");
            }

            if ($type instanceof ReflectionUnionType) {
                throw new ContainerException("Failed to resolve class $id type because pf union type for param $name");
            }

            if ($type instanceof ReflectionNamedType && ! $type->isBuiltin()) {
                return $this->get($type->getName());
            }

            throw new ContainerException("Failed to resolve class $id because of an invalid parameter $type");
        }, $parameters);

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}