<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Tests\Drivers;

use Mockery as m;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use AvtoDev\AppVersion\Tests\AbstractTestCase;
use AvtoDev\AppVersion\Drivers\DriverInterface;
use AvtoDev\AppVersion\Drivers\ConfigFileDriver;
use AvtoDev\AppVersion\Repositories\ConfigFileRepository;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

/**
 * @covers \AvtoDev\AppVersion\Drivers\ConfigFileDriver
 */
class ConfigFileDriverTest extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testImplementation(): void
    {
        $this->assertInstanceOf(
            DriverInterface::class,
            new ConfigFileDriver($this->app->make(ConfigRepository::class), new Filesystem)
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
            ->withArgs(['version.config.build_file'])
            ->once()
            ->andReturn(Str::random())
            ->getMock();

        $driver = new ConfigFileDriver($config_mock, new Filesystem);

        $this->assertInstanceOf(ConfigFileRepository::class, $driver->createRepository());
    }
}
