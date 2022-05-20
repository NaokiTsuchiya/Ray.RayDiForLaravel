<?php

declare(strict_types=1);

namespace Ray\RayDiForLaravel;

use PHPUnit\Framework\TestCase;
use Ray\RayDiForLaravel\Classes\FakeCacheableContext;
use Ray\RayDiForLaravel\Classes\FakeContext;

class ApplicationFactoryTest extends TestCase
{
    private ApplicationFactory $applicationFactory;

    protected function setUp(): void
    {
        $this->applicationFactory = new ApplicationFactory();
    }

    public function testCreateApplication(): void
    {
        $actual = ($this->applicationFactory)(
            __DIR__,
            new FakeContext()
        );

        $this->assertInstanceOf(Application::class, $actual);
    }

    /**
     * @depends testCreateApplication
     */
    public function testNoCache(): void
    {
        $this->assertDirectoryDoesNotExist(__DIR__ . '/tmp/Ray_RayDiForLaravel_Classes_FakeContext');
    }

    public function testCacheableContext(): void
    {
        $actual = ($this->applicationFactory)(
            __DIR__,
            new FakeCacheableContext()
        );

        $this->assertInstanceOf(Application::class, $actual);
    }

    /**
     * @depends testCacheableContext
     */
    public function testCache(): void
    {
        $basePath = __DIR__. '/tmp/Ray_RayDiForLaravel_Classes_FakeCacheableContext';

        $this->assertDirectoryExists($basePath . '/di');
        $this->assertFileExists($basePath . '/di/_aop.txt');
        $this->assertFileExists($basePath . '/di/_module.txt');
        $this->assertFileExists($basePath . '/di/Ray_RayDiForLaravel_Classes_FakeInterceptor-.php');
        $this->assertFileExists($basePath . '/di/Ray_RayDiForLaravel_Classes_GreetingInterface-.php');
        $this->assertDirectoryExists($basePath . '/injector');
    }
}
