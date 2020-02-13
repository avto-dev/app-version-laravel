<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Tests\Commands;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Contracts\Console\Kernel;
use AvtoDev\AppVersion\AppVersionManager;
use AvtoDev\AppVersion\Tests\AbstractTestCase;
use Illuminate\Foundation\Testing\PendingCommand;

/**
 * @covers \AvtoDev\AppVersion\Commands\VersionCommand
 */
class VersionCommandTest extends AbstractTestCase
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

        $this->manager = $this->overrideVersionManager();
        $this->setRandomVersion();
    }

    /**
     * @return void
     */
    public function testExecutionWithHelpArgument(): void
    {
        $this->assertRegExp('~display.+app.+version~i', $this->execute(['--help']));
    }

    /**
     * @return void
     */
    public function testGetMajor(): void
    {
        $this->assertSame(
            $this->manager->repository()->getMajor() . \PHP_EOL,
            $this->execute(['--get-segment' => 'major'])
        );
    }

    /**
     * @return void
     */
    public function testGetMinor(): void
    {
        $this->assertSame(
            $this->manager->repository()->getMinor() . \PHP_EOL,
            $this->execute(['--get-segment' => 'minor'])
        );
    }

    /**
     * @return void
     */
    public function testGetPatch(): void
    {
        $this->assertSame(
            $this->manager->repository()->getPatch() . \PHP_EOL,
            $this->execute(['--get-segment' => 'patch'])
        );
    }

    /**
     * @return void
     */
    public function testGetBuild(): void
    {
        $this->assertSame(
            $this->manager->repository()->getBuild() . \PHP_EOL,
            $this->execute(['--get-segment' => 'build'])
        );
    }

    /**
     * @return void
     */
    public function testExceptionThrownWhenWrongSegmentRequested(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('~unknown.+segment~i');

        $this->execute(['--get-segment' => Str::random()]);
    }

    /**
     * @return void
     */
    public function testVersionSet(): void
    {
        $this->assertNotSame($major = \random_int(100, 119), $this->manager->repository()->getMajor());
        $this->assertNotSame($minor = \random_int(120, 139), $this->manager->repository()->getMinor());
        $this->assertNotSame($patch = \random_int(140, 159), $this->manager->repository()->getPatch());
        $this->assertNotSame($build = Str::random(), $this->manager->repository()->getBuild());

        $this->assertRegExp(
            '~new.+version.+set~i',
            $this->execute(['--set-version' => " v{$major}.{$minor}.{$patch}-{$build}\n\r\t"])
        );

        $this->assertSame($major, $this->manager->repository()->getMajor());
        $this->assertSame($minor, $this->manager->repository()->getMinor());
        $this->assertSame($patch, $this->manager->repository()->getPatch());
        $this->assertSame($build, $this->manager->repository()->getBuild());
    }

    /**
     * @return void
     */
    public function testExceptionThrownWhenWrongVersionSet(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('~wrong.+version~i');

        $this->execute(['--set-version' => Str::random()]);
    }

    /**
     * @return void
     */
    public function testBuildSet(): void
    {
        $this->assertNotSame($build = Str::random(), $this->manager->repository()->getBuild());

        $this->assertRegExp(
            '~build.+set~i',
            $this->execute(['--set-build' => " {$build}\n\r\t"])
        );

        $this->assertSame($build, $this->manager->repository()->getBuild());
    }

    /**
     * @return void
     */
    public function testExecutionWithoutArguments(): void
    {
        $this->assertSame($this->manager->version() . \PHP_EOL, $this->execute());
    }

    /**
     * Execute artisan command and return output.
     *
     * @param array $parameters
     * @param int   $expected_exit_code
     *
     * @return string
     */
    protected function execute(array $parameters = [], int $expected_exit_code = 0): string
    {
        /** @var Kernel $kernel */
        $kernel = $this->app->make(Kernel::class);

        // do NOT use `$this->>artisan(...)`
        $result = $kernel->call('version', $parameters);

        $this->assertSame($expected_exit_code, $result);

        return $kernel->output();
    }
}
