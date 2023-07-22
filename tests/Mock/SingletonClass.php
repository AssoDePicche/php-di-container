<?php

declare(strict_types=1);

namespace Mock;

final class SingletonClass
{
    private static int $instancesCount = 0;

    public function __construct()
    {
        self::$instancesCount++;
    }

    public static function getInstancesCount(): int
    {
        return self::$instancesCount;
    }
}
