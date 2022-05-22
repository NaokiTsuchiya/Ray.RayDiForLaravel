<?php

declare(strict_types=1);

namespace Ray\RayDiForLaravel\Classes;

use Doctrine\Common\Cache\CacheProvider;
use Ray\Compiler\AbstractInjectorContext;
use Ray\Compiler\DiCompileModule;
use Ray\Di\AbstractModule;
use Ray\RayDiForLaravel\LocalCacheProvider;

class FakeCacheableContext extends AbstractInjectorContext
{
    public function __construct(string $tmpDir)
    {
        $dir = str_replace('\\', '_', self::class);
        parent::__construct($tmpDir . '/tmp/' . $dir);
    }

    public function getModule(): AbstractModule
    {
        $module = new Module();
        $module->install(new DiCompileModule(true));

        return $module;
    }

    public function getSavedSingleton(): array
    {
        return [GreetingInterface::class];
    }

    public function getCache(): CacheProvider
    {
        $namespace = str_replace('\\', '_', $this::class);

        return LocalCacheProvider::create($namespace, $this->tmpDir . '/injector');
    }
}
