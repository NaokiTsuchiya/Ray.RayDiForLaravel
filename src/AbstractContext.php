<?php

declare(strict_types=1);

namespace Ray\RayDiForLaravel;

use Ray\Di\AbstractModule;

abstract class AbstractContext
{
    protected string $fromBasePath = 'storage' . DIRECTORY_SEPARATOR . 'ray-di';

    abstract public function getModule(): AbstractModule;

    abstract public function isCacheable(): bool;

    /**
     * @return array<class-string>
     */
    abstract public function getSavedSingleton(): array;

    public function getScriptDir(string $basePath): string
    {
        return $this->buildPath($basePath) . DIRECTORY_SEPARATOR . 'di';
    }

    public function getCacheDir(string $basePath): string
    {
        return $this->buildPath($basePath) . DIRECTORY_SEPARATOR . 'injector';
    }

    private function buildPath(string $basePath): string
    {
        $context = str_replace('\\', '_', $this::class);

        return $basePath . DIRECTORY_SEPARATOR . $this->fromBasePath . DIRECTORY_SEPARATOR . $context;
    }
}
