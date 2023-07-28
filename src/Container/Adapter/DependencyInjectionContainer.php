<?php

declare(strict_types=1);

namespace Container\Adapter;

interface DependencyInjectionContainer
{
    public function autowire(string $className): object;

    /**
     * @template TClassName
     * @param class-string<TClassName> $className
     * @return TClassName
     */
    public function get(string $className): object;

    public function has(string $className): bool;

    public function set(string $className, callable $definition): self;

    public function singleton(string $className, callable $definition): self;
}
