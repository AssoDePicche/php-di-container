# Dependency Injection Container

According to [DotNetTricks](https://www.dotnettricks.com/learn/dependencyinjection/what-is-ioc-container-or-di-container), a DI Container is a framework to create dependencies and inject them automatically when required. It automatically creates objects based on the request and injects them when required. DI Container helps us to manage dependencies within the application in a simple and easy way.

The DI container creates an object of the defined class and also injects all the required dependencies as an object a constructor, a property, or a method that is triggered at runtime and disposes itself at the appropriate time. This process is completed so that we don't have to create and manage objects manually all the time.

These repository contains a DI container for PHP projects.

## Table of Contents

1. [The Interface](#the-interface)

2. [The Implementation](#the-implementation)

3. [Installation](#installation)

4. [Getting Started](#getting-started)

5. [Contributing](#contributing)

6. [Get in Touch](#get-in-touch)

### The Interface

The `Dependency Injection Container` interface defines four methods: `autowire`, `get`, `set` and `singleton`. All these methods expect a class or interface name as a string, but only the last two methods also expect a `callable`, which represents a definition of the class or interface.

```php
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

    public function set(string $className, callable $definition): self;

    public function singleton(string $className, callable $definition): self;
}
```

### The Implementation

The implementation of the "DependencyInjectionContainer" interface is the "DependencyContainer" class and uses the PHP reflection API to manage the instantiation of registered classes.

```php
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

            return $this->singletons[$className];
        };

        return $this;
    }
}
```

### Installation

First, make sure you have [PHP](https://www.php.net/downloads) installed (8.2 version or higher) and then clone this repository or install the library via [composer](https://getcomposer.org/).

- **Installation via Git**

```bash
git clone git@github.com:AssoDePicche/php-di-container.git
```

- **Installation via Composer**

```bash
composer require assodepicche/php-di-container
```

### Getting Started

Instantiate the `DependencyContainer` class

```php

<?php

use Container\Infrastructure\DependencyContainer;

$container = new DependencyContainer;
```

Use the `set` method to set the definition of a class

```php
$containter->set(Foo::class, fn () => new Foo);
```

Call the defined class with the get method whenever you want

```php
$object = $container->get(Foo::class);
```

**Obs:** to make a class a singleton, use the singleton method

```php
$container->singleton(SingletonClass::class);
```

### Contributing

To contribuit to this project [follow these steps](./CONTRIBUTING).

### Get in Touch

Samuel do Prado Rodrigues (AssoDePicche) - <samuelprado730@gmail.com>
