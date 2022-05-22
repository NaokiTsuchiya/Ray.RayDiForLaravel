<?php

declare(strict_types=1);

namespace Ray\RayDiForLaravel;

use Doctrine\Common\Cache\CacheProvider;
use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Symfony\Component\Cache\Adapter\ApcuAdapter;
use Symfony\Component\Cache\Adapter\ChainAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class LocalCacheProvider
{
    public static function create(string $namespace, string $dir): CacheProvider
    {
        $adapters = [new FilesystemAdapter($namespace, directory: $dir)];
        if (ApcuAdapter::isSupported()) {
            $adapters[] = new ApcuAdapter($namespace);
        }

        $cache = DoctrineProvider::wrap(new ChainAdapter($adapters));
        assert($cache instanceof CacheProvider);

        return $cache;
    }
}
