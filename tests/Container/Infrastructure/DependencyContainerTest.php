<?php

declare(strict_types=1);

namespace Container\Infrastructure;

use Container\Adapter\DependencyInjectionContainer;
use Mock\SingletonClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

final class DependencyContainerTest extends TestCase
{
    private DependencyInjectionContainer $container;

    public function setUp(): void
    {
        $this->container = new DependencyContainer;
    }

    #[Test]
    public function dependenciesContainerization(): void
    {
        $className = stdClass::class;

        $this->container->set($className, fn () => new $className);

        $object = $this->container->get($className);

        $this->assertInstanceOf($className, $object);
    }

    #[Test]
    public function autowire(): void
    {
        $className = stdClass::class;

        $object = $this->container->get($className);

        $this->assertInstanceOf($className, $object);
    }

    #[Test]
    public function singleton(): void
    {
        $this->container->singleton(SingletonClass::class, fn () => new SingletonClass);

        $object = $this->container->get(SingletonClass::class);

        $this->assertInstanceOf(SingletonClass::class, $object);

        $this->assertSame(1, $object::getInstancesCount());

        $sameObject = $this->container->get(SingletonClass::class);

        $this->assertSame(1, $sameObject::getInstancesCount());
    }
}
