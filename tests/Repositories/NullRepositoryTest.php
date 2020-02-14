<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Tests\Repositories;

use Illuminate\Support\Str;
use AvtoDev\AppVersion\Tests\AbstractTestCase;
use AvtoDev\AppVersion\Repositories\NullRepository;
use AvtoDev\AppVersion\Repositories\RepositoryInterface;

/**
 * @covers \AvtoDev\AppVersion\Repositories\NullRepository
 */
class NullRepositoryTest extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testImplementation(): void
    {
        $this->assertInstanceOf(RepositoryInterface::class, new NullRepository);
    }

    /**
     * @return void
     */
    public function testSettersNadGetters(): void
    {
        $repository = new NullRepository;

        $this->assertNull($repository->getMajor());
        $this->assertNull($repository->getMinor());
        $this->assertNull($repository->getPatch());
        $this->assertNull($repository->getBuild());

        $repository->setMajor($major = \random_int(1, 20));
        $repository->setMinor($minor = \random_int(21, 40));
        $repository->setPatch($patch = \random_int(41, 60));
        $repository->setBuild($build = Str::random());

        $this->assertSame($major, $repository->getMajor());
        $this->assertSame($minor, $repository->getMinor());
        $this->assertSame($patch, $repository->getPatch());
        $this->assertSame($build, $repository->getBuild());
    }
}
