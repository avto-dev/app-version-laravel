<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Tests\Repositories;

use Mockery as m;
use RuntimeException;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Filesystem\Filesystem;
use AvtoDev\AppVersion\Tests\AbstractTestCase;
use AvtoDev\AppVersion\Repositories\RepositoryInterface;
use AvtoDev\AppVersion\Repositories\ConfigFileRepository;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

/**
 * @covers \AvtoDev\AppVersion\Repositories\ConfigFileRepository
 */
class ConfigFileRepositoryTest extends AbstractTestCase
{
    /**
     * @var ConfigRepository
     */
    protected $config;

    /**
     * @var ConfigFileRepository
     */
    protected $repository;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->config     = $this->app->make(ConfigRepository::class);
        $this->repository = new ConfigFileRepository($this->config, Str::random());
    }

    /**
     * @return void
     */
    public function testImplementation(): void
    {
        $this->assertInstanceOf(RepositoryInterface::class, $this->repository);
    }

    /**
     * @return void
     */
    public function testGetMajor(): void
    {
        $this->config->set('version.config.major', null);
        $this->assertNull($this->repository->getMajor());

        $this->config->set('version.config.major', Str::random());
        $this->assertNull($this->repository->getMajor());

        $this->config->set('version.config.major', $value = \random_int(1, 100));
        $this->assertSame($value, $this->repository->getMajor());
    }

    /**
     * @return void
     */
    public function testSetMajor(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches('~cannot set major~i');

        $this->repository->setMajor(\random_int(1, 100));
    }

    /**
     * @return void
     */
    public function testGetMinor(): void
    {
        $this->config->set('version.config.minor', null);
        $this->assertNull($this->repository->getMinor());

        $this->config->set('version.config.minor', Str::random());
        $this->assertNull($this->repository->getMinor());

        $this->config->set('version.config.minor', $value = \random_int(1, 100));
        $this->assertSame($value, $this->repository->getMinor());
    }

    /**
     * @return void
     */
    public function testSetMinor(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches('~cannot set minor~i');

        $this->repository->setMinor(\random_int(1, 100));
    }

    /**
     * @return void
     */
    public function testGetPatch(): void
    {
        $this->config->set('version.config.patch', null);
        $this->assertNull($this->repository->getPatch());

        $this->config->set('version.config.patch', Str::random());
        $this->assertNull($this->repository->getPatch());

        $this->config->set('version.config.patch', $value = \random_int(1, 100));
        $this->assertSame($value, $this->repository->getPatch());
    }

    /**
     * @return void
     */
    public function testSetPatch(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches('~cannot set patch~i');

        $this->repository->setPatch(\random_int(1, 100));
    }

    /**
     * @return void
     */
    public function testGetBuildUsingConfig(): void
    {
        $this->config->set('version.config.build', null);
        $this->assertNull($this->repository->getBuild());

        $this->config->set('version.config.build', $value = \random_int(1, 100));
        $this->assertNull($this->repository->getBuild());

        $this->config->set('version.config.build', $value = Str::random());
        $this->assertSame($value, $this->repository->getBuild());
    }

    /**
     * @return void
     */
    public function testGetBuildUsingFile(): void
    {
        $this->config->set('version.config.build', Str::random());

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('exists')
            ->withArgs([$build_location = Str::random()])
            ->once()
            ->andReturnTrue()
            ->getMock()
            ->expects('get')
            ->once()
            ->withArgs([$build_location, true])
            ->andReturn("\n  \n\r foo321-build+bar123  ")
            ->getMock();

        $repository = new ConfigFileRepository($this->config, $build_location, $fs_mock);

        $this->assertSame('foo321-build+bar123', $repository->getBuild());
    }

    /**
     * @return void
     */
    public function testGetBuildUsesConfigFileValueIfFileIsEmpty(): void
    {
        $this->config->set('version.config.build', $value = Str::random());

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('exists')
            ->withArgs([$build_location = Str::random()])
            ->once()
            ->andReturnTrue()
            ->getMock()
            ->expects('get')
            ->once()
            ->withArgs([$build_location, true])
            ->andReturn("\n\r   \n\t  ")
            ->getMock();

        $repository = new ConfigFileRepository($this->config, $build_location, $fs_mock);

        $this->assertSame($value, $repository->getBuild());
    }

    /**
     * @return void
     */
    public function testSetBuild(): void
    {
        $this->config->set('version.config.build', Str::random());

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('put')
            ->withArgs([$build_location = Str::random(), $value = Str::random(), true])
            ->once()
            ->andReturn(\mb_strlen($value))
            ->getMock()
            ->expects('exists')
            ->once()
            ->andReturnTrue()
            ->getMock()
            ->expects('get')
            ->withAnyArgs()
            ->andReturn($value)
            ->getMock();

        $repository = new ConfigFileRepository($this->config, $build_location, $fs_mock);

        $repository->setBuild($value);
        $this->assertSame($value, $repository->getBuild());
    }

    /**
     * @return void
     */
    public function testSetBuildThrownExceptionWhenWrongValuePassed(): void
    {
        $values = [
            'foo bar',
            'foo*bar',
            'foo@bar123',
            'bar--baz+ blah.123',
        ];

        $catch_count = 0;

        foreach ($values as $value) {
            try {
                $this->repository->setBuild($value);
            } catch (InvalidArgumentException $e) {
                $this->assertMatchesRegularExpression('~Wrong build value~i', $e->getMessage());
                $catch_count++;
            }
        }

        $this->assertCount($catch_count, $values);
    }

    /**
     * @return void
     */
    public function testSetBuildThrowsExceptionOnPuttingError(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches('~cannot be written~i');

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('put')
            ->withArgs([$build_location = Str::random(), $value = Str::random(), true])
            ->once()
            ->andReturn(0)
            ->getMock();

        $repository = new ConfigFileRepository($this->config, $build_location, $fs_mock);

        $repository->setBuild($value);
    }
}
