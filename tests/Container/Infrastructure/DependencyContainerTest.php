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
    private string  $className = stdClass::class;

    private string $singletonClassName = SingletonClass::class;

    private int $expectedInstancesCount = 1;

    private DependencyInjectionContainer $container;

    public function setUp(): void
    {
        $this->container = new DependencyContainer;
    }

    #[Test]
    public function autowire(): void
    {
        $object = $this->container->get($this->className);

        $this->assertInstanceOf($this->className, $object);
    }

    #[Test]
    public function dependenciesContainerization(): void
    {
        $this->container->set($this->className, fn () => new $this->className);

        $object = $this->container->get($this->className);

        $this->assertInstanceOf($this->className, $object);
    }

    #[Test]
    public function has(): void
    {
        $this->container->set($this->className, fn () => new $this->className);

        $this->assertTrue($this->container->has($this->className));

        $this->assertFalse($this->container->has($this->singletonClassName));
    }

    #[Test]
    public function singleton(): void
    {
        $this->container->singleton($this->singletonClassName, fn () => new $this->singletonClassName);

        $object = $this->container->get($this->singletonClassName);

        $this->assertInstanceOf($this->singletonClassName, $object);

        $this->assertSame($this->expectedInstancesCount, $object::getInstancesCount());

        $sameObject = $this->container->get($this->singletonClassName);

        $this->assertSame($this->expectedInstancesCount, $sameObject::getInstancesCount());
    }
}
