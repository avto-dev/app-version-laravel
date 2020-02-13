<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Tests\Repositories;

use Mockery as m;
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
    public function testGetMajor(): void
    {
        $content = <<<'EOF'
# Changelog

## unreleased

### Changed

- WIP

## v1.2.3-beta+public.1 - 2020-12-30

### Changed

- Update and improvement of Polish translation

## v0.1.2

### Added

- New visual identity
- Version navigation
- Links to latest released version in previous versions

### Changed

- Start using "changelog" over "change log" since it's the common usage

### Removed

- Section about "changelog" vs "CHANGELOG"

## [v0.3.0] - 2020-12-01

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

        $this->assertSame(1, $repository->getMajor());
        $this->assertSame(2, $repository->getMinor());
        $this->assertSame(3, $repository->getPatch());
        $this->assertSame('beta+public.1', $repository->getBuild());
    }
}
