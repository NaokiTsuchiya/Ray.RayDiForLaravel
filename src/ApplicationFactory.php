<?php

declare(strict_types=1);

namespace Ray\RayDiForLaravel;

use Doctrine\Common\Cache\CacheProvider;
use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Ray\Compiler\CachedInjectorFactory;
use Ray\Compiler\DiCompileModule;
use Ray\Di\NullCache;
use Symfony\Component\Cache\Adapter\ApcuAdapter;
use Symfony\Component\Cache\Adapter\ChainAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class ApplicationFactory
{
    public function __invoke(string $basePath, AbstractContext $context): \Illuminate\Foundation\Application
    {
        $cache = $this->getCacheProvider($context, $basePath);

        return new Application(
            $basePath,
            CachedInjectorFactory::getInstance(
                $context::class,
                $context->getScriptDir($basePath),
                static function () use ($context) {
                    $module = $context->getModule();
                    if ($context->isCacheable()) {
                        $module->install(new DiCompileModule(true));
                    }

                    return $module;
                },
                $cache,
                $context->getSavedSingleton()
            )
        );
    }

    private function getCacheProvider(AbstractContext $context, string $basePath): CacheProvider
    {
        if (! $context->isCacheable()) {
            return new NullCache();
        }

        $adapters = [new FilesystemAdapter(directory: $context->getCacheDir($basePath))];
        if (ApcuAdapter::isSupported()) {
            $namespace = str_replace('\\', '_', $context::class);
            $adapters[] = new ApcuAdapter($namespace);
        }

        $cache = DoctrineProvider::wrap(new ChainAdapter($adapters));
        assert($cache instanceof CacheProvider);

        return $cache;
    }
}
