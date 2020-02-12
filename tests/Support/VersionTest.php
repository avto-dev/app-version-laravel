<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Tests\Support;

use Illuminate\Support\Str;
use AvtoDev\AppVersion\Support\Version;
use AvtoDev\AppVersion\Tests\AbstractTestCase;

/**
 * @covers \AvtoDev\AppVersion\Support\Version
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
        $version = new Version;

        $this->assertNull($version->getMajor());
        $this->assertNull($version->getMinor());
        $this->assertNull($version->getPath());
        $this->assertNull($version->getBuild());

        $major = \random_int(1, 20);
        $minor = \random_int(21, 40);
        $patch = \random_int(41, 60);
        $build = Str::random();

        $this->assertInstanceOf(Version::class, $version->setMajor($major));
        $this->assertSame($major, $version->getMajor());

        $this->assertInstanceOf(Version::class, $version->setMinor($minor));
        $this->assertSame($minor, $version->getMinor());

        $this->assertInstanceOf(Version::class, $version->setPath($patch));
        $this->assertSame($patch, $version->getPath());

        $this->assertInstanceOf(Version::class, $version->setBuild($build));
        $this->assertSame($build, $version->getBuild());
    }

    /**
     * @return void
     */
    public function testParseUsingValidData(): void
    {
        $data_sets = [
            '0.0.4'                                                  => [0, 0, 4, null],
            '0.0.0-0'                                                => [0, 0, 0, '0'],
            '1.2.3'                                                  => [1, 2, 3, null],
            '10.20.30'                                               => [10, 20, 30, null],
            '1.1.2-prerelease+meta'                                  => [1, 1, 2, 'prerelease+meta'],
            '1.1.2+meta'                                             => [1, 1, 2, 'meta'],
            '1.1.2+meta-valid'                                       => [1, 1, 2, 'meta-valid'],
            '1.0.0-alpha'                                            => [1, 0, 0, 'alpha'],
            '1.0.0-beta'                                             => [1, 0, 0, 'beta'],
            '1.0.0-alpha.beta'                                       => [1, 0, 0, 'alpha.beta'],
            '1.0.0-alpha.beta.1'                                     => [1, 0, 0, 'alpha.beta.1'],
            '1.0.0-alpha.1'                                          => [1, 0, 0, 'alpha.1'],
            '1.0.0-alpha0.valid'                                     => [1, 0, 0, 'alpha0.valid'],
            '1.0.0-alpha.0valid'                                     => [1, 0, 0, 'alpha.0valid'],
            '1.0.0-alpha-a.b-c-somethinglong+build.1-aef.1-its-okay' => [
                1, 0, 0, 'alpha-a.b-c-somethinglong+build.1-aef.1-its-okay',
            ],
            '1.0.0-rc.1+build.1'                                     => [1, 0, 0, 'rc.1+build.1'],
            '2.0.0-rc.1+build.123'                                   => [2, 0, 0, 'rc.1+build.123'],
            '1.2.3-beta'                                             => [1, 2, 3, 'beta'],
            '10.2.3-DEV-SNAPSHOT'                                    => [10, 2, 3, 'DEV-SNAPSHOT'],
            '1.2.3-SNAPSHOT-123'                                     => [1, 2, 3, 'SNAPSHOT-123'],
            '1.0.0'                                                  => [1, 0, 0, null],
            '2.0.0'                                                  => [2, 0, 0, null],
            '1.1.7'                                                  => [1, 1, 7, null],
            '2.0.0+build.1848'                                       => [2, 0, 0, 'build.1848'],
            '2.0.1-alpha.1227'                                       => [2, 0, 1, 'alpha.1227'],
            '1.0.0-alpha+beta'                                       => [1, 0, 0, 'alpha+beta'],
            '1.2.3----RC-SNAPSHOT.12.9.1--.12+788'                   => [1, 2, 3, '---RC-SNAPSHOT.12.9.1--.12+788'],
            '1.2.3----R-S.12.9.1--.12+meta'                          => [1, 2, 3, '---R-S.12.9.1--.12+meta'],
            '1.2.3----RC-SNAPSHOT.12.9.1--.12'                       => [1, 2, 3, '---RC-SNAPSHOT.12.9.1--.12'],
            '1.0.0+0.build.1-rc.10000aaa-kk-0.1'                     => [1, 0, 0, '0.build.1-rc.10000aaa-kk-0.1'],
            '9999999999999.999999999999.99999999999'                 => [
                9999999999999, 999999999999, 99999999999, null,
            ],
            '1.0.0-0A.is.legal'                                      => [1, 0, 0, '0A.is.legal'],
        ];

        foreach ($data_sets as $version_data => $segments) {
            $version = Version::parse($version_data);

            $this->assertSame($segments[0], $version->getMajor(), $message = "Version value is [{$version_data}]");
            $this->assertSame($segments[1], $version->getMinor(), $message);
            $this->assertSame($segments[2], $version->getPath(), $message);
            $this->assertSame($segments[3], $version->getBuild(), $message);

            $this->assertTrue($version->isValid());
        }
    }

    /**
     * @return void
     */
    public function testParseUsingInvalidSemanticVersionsData(): void
    {
        $data_sets = [
            '1',
            '1.2',
            '1.2.3-0123',
            '1.2.3-0123.0123',
            '1.1.2+.123',
            '+invalid',
            '-invalid',
            '-invalid+invalid',
            '-invalid.01',
            '   -invalid.01',
            'alpha',
            'alpha.beta',
            'alpha.beta.1',
            'alpha.1',
            'alpha+beta',
            'alpha_beta',
            'alpha.',
            'alpha..',
            'beta',
            '1.0.0-alpha_beta',
            '-alpha.',
            '1.0.0-alpha..',
            '1.0.0-alpha..1',
            '1.0.0-alpha...1',
            '1.0.0-alpha....1',
            '1.0.0-alpha.....1',
            '1.0.0-alpha......1',
            '1.0.0-alpha.......1',
            '01.1.1',
            '1.01.1',
            '1.1.01',
            '1.2',
            '1.2.3.DEV',
            '1.2-SNAPSHOT',
            '1.2.31.2.3----RC-SNAPSHOT.12.09.1--..12+788',
            '1.2-RC-SNAPSHOT',
            '-1.0.3-gamma+b7718',
            '+justmeta',
            '9.8.7+meta+meta',
            '9.8.7-whatever+meta+meta',
            '999999999999999999999.9999999999999999.999999999999999----RC-SNAPSHOT.12.09.1---------------------..12',
        ];

        foreach ($data_sets as $version_data) {
            $version = Version::parse($version_data);

            $this->assertNull($version->getMajor(), $message = "Version value is [{$version_data}]");
            $this->assertNull($version->getMinor(), $message);
            $this->assertNull($version->getPath(), $message);
            $this->assertNull($version->getBuild(), $message);

            $this->assertFalse($version->isValid());
        }
    }

    /**
     * @return void
     */
    public function testIsValidUsingCorrectValues(): void
    {
        $values = [
            [0, 0, 0, null],
            [0, 0, 1, 'foo'],
            [0, 0, 1, 'foo+bar'],
            [1, 0, 1, 'foo+bar123'],
            [999999, 999999999, 9999999999999999, 'bar--baz+blah.123'],
        ];

        foreach ($values as $value) {
            $version = (new Version)
                ->setMajor($value[0])
                ->setMinor($value[1])
                ->setPath($value[2])
                ->setBuild($value[3]);

            $this->assertTrue($version->isValid());
        }
    }

    /**
     * @return void
     */
    public function testIsValidUsingIncorrectValues(): void
    {
        $values = [
            [-1, 0, 0, null],
            [0, 0, 1, 'foo bar'],
            [0, 0, -1, 'foo+bar'],
            [1, 0, 1, 'foo@bar123'],
            [999999, -1, 9999999999999999, 'bar--baz+blah.123'],
        ];

        foreach ($values as $value) {
            $version = (new Version)
                ->setMajor($value[0])
                ->setMinor($value[1])
                ->setPath($value[2])
                ->setBuild($value[3]);

            $this->assertFalse($version->isValid());
        }
    }

    /**
     * @return void
     */
    public function testToStringCasting(): void
    {
        $data_sets = [
            '0.0.4'                                                  => [0, 0, 4, null],
            '0.0.0-0'                                                => [0, 0, 0, '0'],
            '1.2.3'                                                  => [1, 2, 3, null],
            '10.20.30'                                               => [10, 20, 30, null],
            '1.1.2-prerelease+meta'                                  => [1, 1, 2, 'prerelease+meta'],
            '1.1.2-meta'                                             => [1, 1, 2, 'meta'],
            '1.1.2-meta-valid'                                       => [1, 1, 2, 'meta-valid'],
            '1.0.0-alpha'                                            => [1, 0, 0, 'alpha'],
            '1.0.0-beta'                                             => [1, 0, 0, 'beta'],
            '1.0.0-alpha.beta'                                       => [1, 0, 0, 'alpha.beta'],
            '1.0.0-alpha.beta.1'                                     => [1, 0, 0, 'alpha.beta.1'],
            '1.0.0-alpha.1'                                          => [1, 0, 0, 'alpha.1'],
            '1.0.0-alpha0.valid'                                     => [1, 0, 0, 'alpha0.valid'],
            '1.0.0-alpha.0valid'                                     => [1, 0, 0, 'alpha.0valid'],
            '1.0.0-alpha-a.b-c-somethinglong+build.1-aef.1-its-okay' => [
                1, 0, 0, 'alpha-a.b-c-somethinglong+build.1-aef.1-its-okay',
            ],
            '1.0.0-rc.1+build.1'                                     => [1, 0, 0, 'rc.1+build.1'],
            '2.0.0-rc.1+build.123'                                   => [2, 0, 0, 'rc.1+build.123'],
            '1.2.3-beta'                                             => [1, 2, 3, 'beta'],
            '10.2.3-DEV-SNAPSHOT'                                    => [10, 2, 3, 'DEV-SNAPSHOT'],
            '1.2.3-SNAPSHOT-123'                                     => [1, 2, 3, 'SNAPSHOT-123'],
            '1.0.0'                                                  => [1, 0, 0, null],
            '2.0.0'                                                  => [2, 0, 0, null],
            '1.1.7'                                                  => [1, 1, 7, null],
            '2.0.0-build.1848'                                       => [2, 0, 0, 'build.1848'],
            '2.0.1-alpha.1227'                                       => [2, 0, 1, 'alpha.1227'],
            '1.0.0-alpha+beta'                                       => [1, 0, 0, 'alpha+beta'],
            '1.2.3----RC-SNAPSHOT.12.9.1--.12+788'                   => [1, 2, 3, '---RC-SNAPSHOT.12.9.1--.12+788'],
            '1.2.3----R-S.12.9.1--.12+meta'                          => [1, 2, 3, '---R-S.12.9.1--.12+meta'],
            '1.2.3----RC-SNAPSHOT.12.9.1--.12'                       => [1, 2, 3, '---RC-SNAPSHOT.12.9.1--.12'],
            '1.0.0-0.build.1-rc.10000aaa-kk-0.1'                     => [1, 0, 0, '0.build.1-rc.10000aaa-kk-0.1'],
            '9999999999999.999999999999.99999999999'                 => [
                9999999999999, 999999999999, 99999999999, null,
            ],
            '1.0.0-0A.is.legal'                                      => [1, 0, 0, '0A.is.legal'],
        ];

        foreach ($data_sets as $expected => $value) {
            $version = (new Version)
                ->setMajor($value[0])
                ->setMinor($value[1])
                ->setPath($value[2])
                ->setBuild($value[3]);

            $this->assertSame($expected, (string) $version);
        }
    }
}
