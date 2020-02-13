<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Tests\Drivers;

use Mockery as m;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use AvtoDev\AppVersion\Drivers\FileDriver;
use AvtoDev\AppVersion\Tests\AbstractTestCase;
use AvtoDev\AppVersion\Drivers\DriverInterface;
use AvtoDev\AppVersion\Repositories\FileRepository;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

/**
 * @covers \AvtoDev\AppVersion\Drivers\FileDriver
 */
class FileDriverTest extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testImplementation(): void
    {
        $this->assertInstanceOf(
            DriverInterface::class,
            new FileDriver($this->app->make(ConfigRepository::class), new Filesystem)
        );
    }

    /**
     * @return void
     */
    public function testCreateRepository(): void
    {
        /** @var m\MockInterface|ConfigRepository $config_mock */
        $config_mock = m::mock(ConfigRepository::class)
            ->expects('get')
            ->withArgs(['version.file.path'])
            ->once()
            ->andReturn(Str::random())
            ->getMock();

        $driver = new FileDriver($config_mock, new Filesystem);

        $this->assertInstanceOf(FileRepository::class, $driver->createRepository());
    }
}
