<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Tests\Repository;

use Illuminate\Support\Str;
use AvtoDev\AppVersion\Repository\Version;
use AvtoDev\AppVersion\Tests\AbstractTestCase;

/**
 * @covers \AvtoDev\AppVersion\Repository\Version
 */
class VersionTest extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testGetters(): void
    {
        $version = new Version(
            $major = \random_int(1, 20),
            $minor = \random_int(21, 40),
            $patch = \random_int(41, 60),
            $build = Str::random()
        );

        $this->assertSame($major, $version->getMajor());
        $this->assertSame($minor, $version->getMinor());
        $this->assertSame($patch, $version->getPath());
        $this->assertSame($build, $version->getBuild());
    }

    /**
     * @return void
     */
    public function testSetters(): void
    {
        $major = \random_int(1, 20);
        $minor = \random_int(21, 40);
        $patch = \random_int(41, 60);
        $build = Str::random();

        $version = new Version();

        $this->assertInstanceOf(Version::class, $version->setMajor($major));
        $this->assertSame($major, $version->getMajor());

        $this->assertInstanceOf(Version::class, $version->setMinor($minor));
        $this->assertSame($minor, $version->getMinor());

        $this->assertInstanceOf(Version::class, $version->setPath($patch));
        $this->assertSame($patch, $version->getPath());

        $this->assertInstanceOf(Version::class, $version->setBuild($build));
        $this->assertSame($build, $version->getBuild());
    }
}
