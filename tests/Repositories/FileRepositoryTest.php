<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Tests\Repositories;

use Mockery as m;
use RuntimeException;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use AvtoDev\AppVersion\Tests\AbstractTestCase;
use AvtoDev\AppVersion\Repositories\FileRepository;
use AvtoDev\AppVersion\Repositories\RepositoryInterface;

/**
 * @covers \AvtoDev\AppVersion\Repositories\FileRepository
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
    public function testVersionSegmentGettersWithData(): void
    {
        $major = \random_int(1, 20);
        $minor = \random_int(21, 40);
        $patch = \random_int(41, 60);
        $build = Str::random() . (\random_int(0, 1) === 1
                ? '+' . Str::random()
                : '');

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->times(4)
            ->withArgs([$file_location = Str::random(), true])
            ->andReturn("{$major}.{$minor}.{$patch}-{$build}")
            ->getMock();

        $repository = new FileRepository($file_location, $fs_mock);

        $this->assertSame($major, $repository->getMajor());
        $this->assertSame($minor, $repository->getMinor());
        $this->assertSame($patch, $repository->getPath());
        $this->assertSame($build, $repository->getBuild());
    }

    /**
     * @return void
     */
    public function testVersionSegmentGettersWithEmptyData(): void
    {
        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->times(4)
            ->withArgs([$file_location = Str::random(), true])
            ->andReturn('')
            ->getMock();

        $repository = new FileRepository($file_location, $fs_mock);

        $this->assertNull($repository->getMajor());
        $this->assertNull($repository->getMinor());
        $this->assertNull($repository->getPath());
        $this->assertNull($repository->getBuild());
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
    public function testValuesGettersWithExtraAtBeginningAndEnd(): void
    {
        $major = \random_int(1, 20);
        $minor = \random_int(21, 40);
        $patch = \random_int(41, 60);
        $build = Str::random() . (\random_int(0, 1) === 1
                ? '+' . Str::random()
                : '');

        $data_sets = [
            "\t\n\r {$major}.{$minor}.{$patch}-{$build} \x0B ",
            "  \n   \n{$major}.{$minor}.{$patch}-{$build}",
            "\0{$major}.{$minor}.{$patch}-{$build}\r\n\r\n",
        ];

        foreach ($data_sets as $data_set) {
            /** @var m\MockInterface|Filesystem $fs_mock */
            $fs_mock = m::mock(Filesystem::class)
                ->expects('get')
                ->times(4)
                ->withArgs([$file_location = Str::random(), true])
                ->andReturn($data_set)
                ->getMock();

            $repository = new FileRepository($file_location, $fs_mock);

            $this->assertSame($major, $repository->getMajor());
            $this->assertSame($minor, $repository->getMinor());
            $this->assertSame($patch, $repository->getPath());
            $this->assertSame($build, $repository->getBuild());
        }
    }

    /**
     * @return void
     */
    public function testValuesGettersWithValidData(): void
    {
        $data_sets = [
            '0.0.4'                 => [0, 0, 4, null],
            '1.2.3'                 => [1, 2, 3, null],
            '10.20.30'              => [10, 20, 30, null],
            '0.0.0-0'               => [0, 0, 0, '0'],
            '1.1.2-prerelease+meta' => [1, 1, 2, 'prerelease+meta'],
            '1.1.2+meta'            => [1, 1, 2, 'meta'],
            '1.0.0-rc.1+build.1'    => [1, 0, 0, 'rc.1+build.1'],
            '2.0.0-rc.1+build.123'  => [2, 0, 0, 'rc.1+build.123'],
            '1.2.3-beta'            => [1, 2, 3, 'beta'],
            '10.2.3-DEV-SNAPSHOT'   => [10, 2, 3, 'DEV-SNAPSHOT'],
            '1.2.3-SNAPSHOT-123'    => [1, 2, 3, 'SNAPSHOT-123'],
            '1.0.0-0A.is.legal'     => [1, 0, 0, '0A.is.legal'],
        ];

        foreach ($data_sets as $version_data => $segments) {
            /** @var m\MockInterface|Filesystem $fs_mock */
            $fs_mock = m::mock(Filesystem::class)
                ->expects('get')
                ->times(4)
                ->withArgs([$file_location = Str::random(), true])
                ->andReturn($version_data)
                ->getMock();

            $repository = new FileRepository($file_location, $fs_mock);

            $this->assertSame($segments[0], $repository->getMajor(), $message = "Version value is [{$version_data}]");
            $this->assertSame($segments[1], $repository->getMinor(), $message);
            $this->assertSame($segments[2], $repository->getPath(), $message);
            $this->assertSame($segments[3], $repository->getBuild(), $message);
        }
    }

    /**
     * @return void
     */
    public function testValuesGettersWithInvalidSemanticVersionsData(): void
    {
        $data_sets = [
            '1',
            '1.2',
            '1.2.3-0123',
            '1.2.3-0123.0123',
            '1.1.2+.123',
            'alpha_beta',
            'alpha.',
            'alpha..',
            'beta',
            '1.0.0-alpha_beta',
            '-alpha.',
            '1.0.0-alpha..',
            '1.0.0-alpha..1',
            '01.1.1',
            '1.01.1',
            '1.1.01',
            '1.2',
            '1.2.3.DEV',
            '1.2-SNAPSHOT',
        ];

        foreach ($data_sets as $version_data) {
            /** @var m\MockInterface|Filesystem $fs_mock */
            $fs_mock = m::mock(Filesystem::class)
                ->expects('get')
                ->times(4)
                ->withArgs([$file_location = Str::random(), true])
                ->andReturn($version_data)
                ->getMock();

            $repository = new FileRepository($file_location, $fs_mock);

            $this->assertNull($repository->getMajor(), $message = "Version value is [{$version_data}]");
            $this->assertNull($repository->getMinor(), $message);
            $this->assertNull($repository->getPath(), $message);
            $this->assertNull($repository->getBuild(), $message);
        }
    }

    /**
     * @return void
     */
    public function testSetBuildThrowsExceptionOnInvalidValuePassed(): void
    {
        $values = [
            'foo bar',
            'build~1',
            'build@v1',
            '11.22 33',
            'foo-bar...1=4',
            'built:never',
        ];

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->times(\count($values))
            ->withAnyArgs()
            ->andReturn('1.2.3-4')
            ->getMock();

        $repository  = new FileRepository(Str::random(), $fs_mock);
        $catch_count = 0;

        foreach ($values as $value) {
            try {
                $repository->setBuild($value);
            } catch (\InvalidArgumentException $e) {
                $this->assertRegExp('~Wrong.*version~i', $e->getMessage());
                $catch_count++;
            }
        }

        $this->assertCount($catch_count, $values);
    }

    /**
     * @return void
     */
    public function testGetMajorThrowsExceptionOnFilesystemGet(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~not exist~i');

        (new FileRepository(Str::random(), $this->getFilesystemMockWithGetError()))->getMajor();
    }

    /**
     * @return void
     */
    public function testSetMajorThrowsExceptionOnFilesystemPutError(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~cannot be written~i');

        (new FileRepository(Str::random(), $this->getFilesystemMockWithPutError()))->setMajor(\random_int(1, 100));
    }

    /**
     * @return void
     */
    public function testGetMinorThrowsExceptionOnFilesystemGet(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~not exist~i');

        (new FileRepository(Str::random(), $this->getFilesystemMockWithGetError()))->getMinor();
    }

    /**
     * @return void
     */
    public function testSetMinorThrowsExceptionOnFilesystemPutError(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~cannot be written~i');

        (new FileRepository(Str::random(), $this->getFilesystemMockWithPutError()))->setMinor(\random_int(1, 100));
    }

    /**
     * @return void
     */
    public function testGetPathThrowsExceptionOnFilesystemGet(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~not exist~i');

        (new FileRepository(Str::random(), $this->getFilesystemMockWithGetError()))->getPath();
    }

    /**
     * @return void
     */
    public function testSetPathThrowsExceptionOnFilesystemPutError(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~cannot be written~i');

        (new FileRepository(Str::random(), $this->getFilesystemMockWithPutError()))->setPath(\random_int(1, 100));
    }

    /**
     * @return void
     */
    public function testGetBuildThrowsExceptionOnFilesystemGet(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~not exist~i');

        (new FileRepository(Str::random(), $this->getFilesystemMockWithGetError()))->getBuild();
    }

    /**
     * @return void
     */
    public function testSetBuildThrowsExceptionOnFilesystemPutError(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~cannot be written~i');

        (new FileRepository(Str::random(), $this->getFilesystemMockWithPutError()))->setBuild(Str::random());
    }

    /**
     * @return m\MockInterface|Filesystem
     */
    protected function getFilesystemMockWithPutError(): m\MockInterface
    {
        return m::mock(Filesystem::class)
            ->expects('get')
            ->withAnyArgs()
            ->andReturn('1.2.3-123')
            ->getMock()
            ->expects('put')
            ->withAnyArgs()
            ->andReturn(\random_int(0, 1) === 1
                ? 0
                : false)
            ->getMock();
    }

    /**
     * @return m\MockInterface|Filesystem
     */
    protected function getFilesystemMockWithGetError(): m\MockInterface
    {
        return m::mock(Filesystem::class)
            ->expects('get')
            ->withAnyArgs()
            ->andThrow(\Illuminate\Contracts\Filesystem\FileNotFoundException::class)
            ->getMock();
    }
}
