<?php

namespace AvtoDev\AppVersion\Tests\Commands;

use AvtoDev\AppVersion\Contracts\AppVersionManagerContract;

/**
 * @covers \AvtoDev\AppVersion\Commands\VersionCommand<extended>
 */
class VersionCommandTest extends AbstractCommandTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        // Make clear
        foreach (glob(storage_path('app/APP*')) as $file_path) {
            unlink($file_path);
        }

        parent::tearDown();
    }

    /**
     * Test command execution.
     *
     * @return void
     */
    public function testExecution(): void
    {
        /** @var AppVersionManagerContract $manager */
        $manager = $this->app->make(AppVersionManagerContract::class);
        $manager->setBuild('pre-alpha');

        $this->assertNotFalse($this->artisan($this->getCommandSignature()));
        $this->assertEquals('1.0.0-pre-alpha' . PHP_EOL, $this->console()->output());

        foreach (['--build', '-b'] as $option) {
            $this->assertNotFalse($this->artisan($this->getCommandSignature(), [$option => true]));
            $this->assertEquals('pre-alpha' . PHP_EOL, $this->console()->output());
        }

        $this->assertNotFalse($this->artisan($this->getCommandSignature(), ['--set-build' => 'beta2']));
        $this->assertContains('build version changed', $this->console()->output());
        $this->assertNotFalse($this->artisan($this->getCommandSignature()));
        $this->assertEquals('1.0.0-beta2' . PHP_EOL, $this->console()->output());

        $this->assertNotFalse($this->artisan($this->getCommandSignature(), ['--refresh' => true]));
        $this->assertContains('files values updated', $this->console()->output());
        $this->assertNotFalse($this->artisan($this->getCommandSignature()));
        $this->assertEquals('1.0.0-beta2' . PHP_EOL, $this->console()->output());
    }

    /**
     * {@inheritdoc}
     */
    protected function getCommandSignature(): string
    {
        return 'version';
    }
}
