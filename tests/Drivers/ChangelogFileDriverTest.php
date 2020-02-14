<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Tests\Drivers;

use Mockery as m;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use AvtoDev\AppVersion\Tests\AbstractTestCase;
use AvtoDev\AppVersion\Drivers\DriverInterface;
use AvtoDev\AppVersion\Drivers\ChangelogFileDriver;
use AvtoDev\AppVersion\Repositories\ChangelogFileRepository;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

/**
 * @covers \AvtoDev\AppVersion\Drivers\ChangelogFileDriver
 */
class ChangelogFileDriverTest extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testImplementation(): void
    {
        $this->assertInstanceOf(
            DriverInterface::class,
            new ChangelogFileDriver($this->app->make(ConfigRepository::class), new Filesystem)
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
            ->withArgs(['version.changelog.path'])
            ->once()
            ->andReturn($file_location = Str::random())
            ->getMock();

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->once()
            ->withArgs([$file_location, true])
            ->andReturn("# Changelog\n## v1.2.3\n### Changes\n- WIP\n")
            ->getMock();

        $driver = new ChangelogFileDriver($config_mock, $fs_mock);

        $this->assertInstanceOf(ChangelogFileRepository::class, $repository = $driver->createRepository());

        $repository->getMajor();
    }
}
