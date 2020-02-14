<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Tests\Repositories;

use Mockery as m;
use RuntimeException;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use AvtoDev\AppVersion\Tests\AbstractTestCase;
use AvtoDev\AppVersion\Repositories\ChangelogFileRepository;

/**
 * @covers \AvtoDev\AppVersion\Repositories\ChangelogFileRepository
 */
class ChangelogFileRepositoryTest extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testGettersUsingValidMarkdownContent(): void
    {
        $values = [
            'v100.102.103-beta+public.1 - 2020-12-30' => [100, 102, 103, 'beta+public.1'],
            'v0.1.2'                                  => [0, 1, 2, null],
            '0.1.2-beta'                              => [0, 1, 2, 'beta'],
            '[v0.3.0] - 2020-12-01'                   => [0, 3, 0, null],
        ];

        foreach ($values as $version_string => $value) {

            $content = <<<EOF
# Changelog

## unreleased

### Changed

- WIP

## {$version_string}

### Changed

- Update and improvement of Polish translation

## v0.0.1

### Added

- New visual identity
- Version navigation
- Links to latest released version in previous versions

### Changed

- Start using "changelog" over "change log" since it's the common usage

### Removed

- Section about "changelog" vs "CHANGELOG"

## [v0.0.0-alpha] - 2020-12-01

### Added

- RU translation
- pt-BR translation
- es-ES translation

[v0.3.0]:https://github.com/
EOF;

            /** @var m\MockInterface|Filesystem $fs_mock */
            $fs_mock = m::mock(Filesystem::class)
                ->expects('get')
                ->times(4)
                ->withArgs([$file_location = Str::random(), true])
                ->andReturn($content)
                ->getMock();

            $repository = new ChangelogFileRepository($file_location, $fs_mock);

            $this->assertSame($value[0], $repository->getMajor());
            $this->assertSame($value[1], $repository->getMinor());
            $this->assertSame($value[2], $repository->getPatch());
            $this->assertSame($value[3], $repository->getBuild());
        }
    }

    /**
     * @return void
     */
    public function testExceptionThrowsWhenVersionHeaderCannotBeExtracted(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~Cannot extract latest version~i');

        $content = <<<EOF
# Changelog

## unreleased

### Changed

- WIP

## v2.0

### Changed

- Update and improvement of Polish translation

## v1.O.1

### Added

- New visual identity
- Version navigation
- Links to latest released version in previous versions

### Changed

- Start using "changelog" over "change log" since it's the common usage

### Removed

- Section about "changelog" vs "CHANGELOG"

## [ v0.0.a1] - 2020-12-01

### Added

- RU translation
- pt-BR translation
- es-ES translation

[v0.3.0]:https://github.com/
EOF;

        /** @var m\MockInterface|Filesystem $fs_mock */
        $fs_mock = m::mock(Filesystem::class)
            ->expects('get')
            ->once()
            ->withArgs([$file_location = Str::random(), true])
            ->andReturn($content)
            ->getMock();

        (new ChangelogFileRepository($file_location, $fs_mock))->getPatch();
    }

    /**
     * @return void
     */
    public function testSetMajorAlwaysThrowsException(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~cannot set major~i');

        (new ChangelogFileRepository(Str::random()))->setMajor(\random_int(1, 100));
    }

    /**
     * @return void
     */
    public function testSetMinorAlwaysThrowsException(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~cannot set minor~i');

        (new ChangelogFileRepository(Str::random()))->setMinor(\random_int(1, 100));
    }

    /**
     * @return void
     */
    public function testSetPatchAlwaysThrowsException(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~cannot set patch~i');

        (new ChangelogFileRepository(Str::random()))->setPatch(\random_int(1, 100));
    }

    /**
     * @return void
     */
    public function testSetBuildAlwaysThrowsException(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~cannot set build~i');

        (new ChangelogFileRepository(Str::random()))->setBuild(Str::random());
    }

    /**
     * @return void
     */
    public function testGetMajorThrowsExceptionOnFilesystemGet(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~not exist~i');

        (new ChangelogFileRepository(Str::random(), $this->getFilesystemMockWithGetError()))->getMajor();
    }

    /**
     * @return void
     */
    public function testGetMinorThrowsExceptionOnFilesystemGet(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~not exist~i');

        (new ChangelogFileRepository(Str::random(), $this->getFilesystemMockWithGetError()))->getMinor();
    }

    /**
     * @return void
     */
    public function testGetPatchThrowsExceptionOnFilesystemGet(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~not exist~i');

        (new ChangelogFileRepository(Str::random(), $this->getFilesystemMockWithGetError()))->getPatch();
    }

    /**
     * @return void
     */
    public function testGetBuildThrowsExceptionOnFilesystemGet(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('~not exist~i');

        (new ChangelogFileRepository(Str::random(), $this->getFilesystemMockWithGetError()))->getBuild();
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
