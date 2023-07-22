<?php

declare(strict_types=1);

namespace Container\Infrastructure;

use Container\Adapter\DependencyInjectionContainer;
use ReflectionClass;
use ReflectionParameter;

final class DependencyContainer implements DependencyInjectionContainer
{
    private array $definitions = [];

    private array $singletons = [];

    public function autowire(string $className): object
    {
        $reflectionClass = new ReflectionClass($className);

        $constructorParameters = array_map(
            fn (ReflectionParameter $parameter) => $this->get($parameter->getType()->getName()),
            $reflectionClass->getConstructor()?->getParameters() ?? []
        );

        return new $className(...$constructorParameters);
    }

    public function get(string $className): object
    {
        if ($instance = $this->singletons[$className] ?? null) {
            return $instance;
        }

        $definition = $this->definitions[$className] ?? $this->autowire(...);

        return $definition($className);
    }

    public function set(string $className, callable $definition): self
    {
        $this->definitions[$className] = $definition;

        return $this;
    }

    public function singleton(string $className, callable $definition): self
    {
        $this->definitions[$className] = function () use ($className, $definition) {
            $this->singletons[$className] = $definition($this);
        };

        return $this;
    }
}
