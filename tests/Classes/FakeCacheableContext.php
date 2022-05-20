<?php

declare(strict_types=1);

namespace Ray\RayDiForLaravel\Classes;

use Ray\Di\AbstractModule;
use Ray\RayDiForLaravel\AbstractContext;

class FakeCacheableContext extends AbstractContext
{
    protected string $fromBasePath = 'tmp';

    public function getModule(): AbstractModule
    {
        return new Module();
    }

    public function isCacheable(): bool
    {
        return true;
    }

    public function getSavedSingleton(): array
    {
        return [GreetingInterface::class];
    }
}
