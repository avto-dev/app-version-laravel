<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Tests\Repository;

use Mockery as m;
use RuntimeException;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use AvtoDev\AppVersion\Tests\AbstractTestCase;
use AvtoDev\AppVersion\Repository\FileRepository;
use AvtoDev\AppVersion\Repository\RepositoryInterface;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

/**
 * @covers \AvtoDev\AppVersion\Repository\FileRepository
 */
class FileRepositoryTest extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testImplementation(): void
    {
        $this->assertInstanceOf(RepositoryInterface::class, new FileRepository(Str::random()));
    }

    /**
     * @return void
     */
    public function testGetMajor(): void
    {
        $major = \random_int(1, 100);

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->withArgs([$file_location = Str::random(), true])
            ->andReturn("{$major}.2.3-123")
            ->getMock();

        $this->assertSame($major, (new FileRepository($file_location, $fs_mock))->getMajor());

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->withArgs([$file_location = Str::random(), true])
            ->andReturn('')
            ->getMock();

        $this->assertNull((new FileRepository($file_location, $fs_mock))->getMajor());
    }

    /**
     * @return void
     */
    public function testSetMajor(): void
    {
        $major = \random_int(1, 100);

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->withArgs([$file_location = Str::random(), true])
            ->andReturn('0.2.3-123')
            ->getMock()
            ->expects('put')
            ->withArgs([$file_location, $new_version = "{$major}.2.3-123", true])
            ->andReturn(\mb_strlen($new_version))
            ->getMock();

        (new FileRepository($file_location, $fs_mock))->setMajor($major);

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->withAnyArgs()
            ->andReturn('0.2.3')
            ->getMock()
            ->expects('put')
            ->withArgs([$file_location, $new_version = "{$major}.2.3", true])
            ->andReturn(\mb_strlen($new_version))
            ->getMock();

        (new FileRepository($file_location, $fs_mock))->setMajor($major);
    }

    /**
     * @return void
     */
    public function testGetMajorException(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~not exist~i');

        /** @var m\MockInterface|m\Expectation|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->withAnyArgs()
            ->andThrow(FileNotFoundException::class)
            ->getMock();

        (new FileRepository(Str::random(), $fs_mock))->getMajor();
    }

    /**
     * @return void
     */
    public function testSetMajorException(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~cannot be written~i');

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->withAnyArgs()
            ->andReturn('1.2.3-123')
            ->getMock()
            ->expects('put')
            ->withAnyArgs()
            ->andReturnFalse()
            ->getMock();

        (new FileRepository(Str::random(), $fs_mock))->setMajor(\random_int(1, 100));
    }

    /**
     * @return void
     */
    public function testGetMinor(): void
    {
        $minor = \random_int(1, 100);

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->withArgs([$file_location = Str::random(), true])
            ->andReturn("0.{$minor}.3-123")
            ->getMock();

        $this->assertSame($minor, (new FileRepository($file_location, $fs_mock))->getMinor());

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->withArgs([$file_location = Str::random(), true])
            ->andReturn('')
            ->getMock();

        $this->assertNull((new FileRepository($file_location, $fs_mock))->getMinor());
    }

    /**
     * @return void
     */
    public function testSetMinor(): void
    {
        $minor = \random_int(1, 100);

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->withArgs([$file_location = Str::random(), true])
            ->andReturn('0.2.3-123')
            ->getMock()
            ->expects('put')
            ->withArgs([$file_location, $new_version = "0.{$minor}.3-123", true])
            ->andReturn(\mb_strlen($new_version))
            ->getMock();

        (new FileRepository($file_location, $fs_mock))->setMinor($minor);

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->withAnyArgs()
            ->andReturn('0.2.3')
            ->getMock()
            ->expects('put')
            ->withArgs([$file_location, $new_version = "0.{$minor}.3", true])
            ->andReturn(\mb_strlen($new_version))
            ->getMock();

        (new FileRepository($file_location, $fs_mock))->setMinor($minor);
    }

    /**
     * @return void
     */
    public function testGetMinorException(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~not exist~i');

        /** @var m\MockInterface|m\Expectation|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->withAnyArgs()
            ->andThrow(FileNotFoundException::class)
            ->getMock();

        (new FileRepository(Str::random(), $fs_mock))->getMinor();
    }

    /**
     * @return void
     */
    public function testSetMinorException(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~cannot be written~i');

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->withAnyArgs()
            ->andReturn('1.2.3-123')
            ->getMock()
            ->expects('put')
            ->withAnyArgs()
            ->andReturnFalse()
            ->getMock();

        (new FileRepository(Str::random(), $fs_mock))->setMinor(\random_int(1, 100));
    }

    /**
     * @return void
     */
    public function testGetPath(): void
    {
        $path = \random_int(1, 100);

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->withArgs([$file_location = Str::random(), true])
            ->andReturn("0.0.{$path}-123")
            ->getMock();

        $this->assertSame($path, (new FileRepository($file_location, $fs_mock))->getPath());

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->withArgs([$file_location = Str::random(), true])
            ->andReturn('')
            ->getMock();

        $this->assertNull((new FileRepository($file_location, $fs_mock))->getPath());
    }

    /**
     * @return void
     */
    public function testSetPath(): void
    {
        $path = \random_int(1, 100);

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->withArgs([$file_location = Str::random(), true])
            ->andReturn('0.0.3-123')
            ->getMock()
            ->expects('put')
            ->withArgs([$file_location, $new_version = "0.0.{$path}-123", true])
            ->andReturn(\mb_strlen($new_version))
            ->getMock();

        (new FileRepository($file_location, $fs_mock))->setPath($path);

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->withAnyArgs()
            ->andReturn('0.0.3')
            ->getMock()
            ->expects('put')
            ->withArgs([$file_location, $new_version = "0.0.{$path}", true])
            ->andReturn(\mb_strlen($new_version))
            ->getMock();

        (new FileRepository($file_location, $fs_mock))->setPath($path);
    }

    /**
     * @return void
     */
    public function testGetPathException(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~not exist~i');

        /** @var m\MockInterface|m\Expectation|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->withAnyArgs()
            ->andThrow(FileNotFoundException::class)
            ->getMock();

        (new FileRepository(Str::random(), $fs_mock))->getPath();
    }

    /**
     * @return void
     */
    public function testSetPathException(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~cannot be written~i');

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->withAnyArgs()
            ->andReturn('1.2.3-123')
            ->getMock()
            ->expects('put')
            ->withAnyArgs()
            ->andReturnFalse()
            ->getMock();

        (new FileRepository(Str::random(), $fs_mock))->setPath(\random_int(1, 100));
    }

    /**
     * @return void
     */
    public function testGetBuild(): void
    {
        $build = Str::random();

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->withArgs([$file_location = Str::random(), true])
            ->andReturn("0.0.0-{$build}")
            ->getMock();

        $this->assertSame($build, (new FileRepository($file_location, $fs_mock))->getBuild());

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->withArgs([$file_location = Str::random(), true])
            ->andReturn('')
            ->getMock();

        $this->assertNull((new FileRepository($file_location, $fs_mock))->getBuild());
    }

    /**
     * @return void
     */
    public function testSetBuild(): void
    {
        $build = Str::random();

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->withArgs([$file_location = Str::random(), true])
            ->andReturn('0.0.0-123')
            ->getMock()
            ->expects('put')
            ->withArgs([$file_location, $new_version = "0.0.0-{$build}", true])
            ->andReturn(\mb_strlen($new_version))
            ->getMock();

        (new FileRepository($file_location, $fs_mock))->setBuild($build);
    }

    /**
     * @return void
     */
    public function testGetBuildException(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~not exist~i');

        /** @var m\MockInterface|m\Expectation|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->withAnyArgs()
            ->andThrow(FileNotFoundException::class)
            ->getMock();

        (new FileRepository(Str::random(), $fs_mock))->getBuild();
    }

    /**
     * @return void
     */
    public function testSetBuildException(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~cannot be written~i');

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->withAnyArgs()
            ->andReturn('1.2.3-123')
            ->getMock()
            ->expects('put')
            ->withAnyArgs()
            ->andReturn(0)
            ->getMock();

        (new FileRepository(Str::random(), $fs_mock))->setBuild(Str::random());
    }
}
