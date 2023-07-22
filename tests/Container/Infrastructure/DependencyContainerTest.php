<?php

declare(strict_types=1);

namespace Container\Infrastructure;

use Container\Adapter\DependencyInjectionContainer;
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
}
