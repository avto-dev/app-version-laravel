<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Tests;

use AvtoDev\AppVersion\AppVersionManager;
use AvtoDev\AppVersion\AppVersionManagerInterface;
use AvtoDev\AppVersion\Repositories\NullRepository;

/**
 * @covers \AvtoDev\AppVersion\AppVersionManager
 */
class AppVersionManagerTest extends AbstractTestCase
{
    /**
     * @var AppVersionManager
     */
    protected $manager;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = new AppVersionManager(new NullRepository);
        $this->setRandomVersion($this->manager);
    }

    /**
     * @return void
     */
    public function testImplementation(): void
    {
        $this->assertInstanceOf(AppVersionManagerInterface::class, $this->manager);
    }

    /**
     * @return void
     */
    public function testVersion(): void
    {
        $repository = $this->manager->repository();

        $this->assertSame(
            "{$repository->getMajor()}.{$repository->getMinor()}.{$repository->getPatch()}-{$repository->getBuild()}",
            $this->manager->version()
        );

        $repository->setBuild('');
        $this->assertSame(
            "{$repository->getMajor()}.{$repository->getMinor()}.{$repository->getPatch()}",
            $this->manager->version()
        );
    }

    /**
     * @return void
     */
    public function testFormatted(): void
    {
        $repository = $this->manager->repository();

        $this->assertSame(
            "{$repository->getMajor()}.{$repository->getMinor()}.{$repository->getPatch()}-{$repository->getBuild()}",
            $this->manager->formatted()
        );

        $this->assertSame(
            "{$repository->getMajor()}#{$repository->getMinor()}",
            $this->manager->formatted('{major}#{minor}')
        );

        $this->assertSame(
            "{$repository->getBuild()}__{$repository->getPatch()}++{$repository->getMinor()}=={$repository->getMajor()}",
            $this->manager->formatted('{build}__{patch}++{minor}=={major}')
        );
    }

    /**
     * @return void
     */
    public function testHashed(): void
    {
        $this->assertRegExp('~[a-z0-9]{5}~', $this->manager->hashed(5));

        $this->assertSame(6, \mb_strlen($this->manager->hashed()));
        $this->assertSame(8, \mb_strlen($this->manager->hashed(8)));
        $this->assertSame(2, \mb_strlen($this->manager->hashed(2)));
        $this->assertSame(40, \mb_strlen($this->manager->hashed(666)));
    }

    /**
     * @return void
     */
    public function testRepository(): void
    {
        $manager = new AppVersionManager($repository = new NullRepository);

        $this->assertSame($repository, $manager->repository());
    }
}
