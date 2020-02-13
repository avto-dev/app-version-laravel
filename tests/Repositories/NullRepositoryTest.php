<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Tests\Repositories;

use Illuminate\Support\Str;
use AvtoDev\AppVersion\Tests\AbstractTestCase;
use AvtoDev\AppVersion\Repositories\NullRepository;

/**
 * @covers \AvtoDev\AppVersion\Repositories\NullRepository
 */
class NullRepositoryTest extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testSettersNadGetters(): void
    {
        $repository = new NullRepository;

        $this->assertNull($repository->getMajor());
        $this->assertNull($repository->getMinor());
        $this->assertNull($repository->getPath());
        $this->assertNull($repository->getBuild());

        $repository->setMajor($major = \random_int(1, 20));
        $repository->setMinor($minor = \random_int(21, 40));
        $repository->setPath($path = \random_int(41, 60));
        $repository->setBuild($build = Str::random());

        $this->assertSame($major, $repository->getMajor());
        $this->assertSame($minor, $repository->getMinor());
        $this->assertSame($path, $repository->getPath());
        $this->assertSame($build, $repository->getBuild());
    }
}
