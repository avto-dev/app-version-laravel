<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Tests;

use AvtoDev\AppVersion\Contracts\AppVersionManagerContract;

class BladeRenderTest extends AbstractTestCase
{
    /**
     * Rendering test.
     *
     * @covers \AvtoDev\AppVersion\ServiceProvider::registerBlade
     *
     * @return void
     */
    public function testRendering(): void
    {
        /** @var AppVersionManagerContract $manager */
        $manager = $this->app->make(AppVersionManagerContract::class);
        $manager->setBuild($build = 'pre-beta 2');

        view()->addNamespace('stubs', __DIR__ . '/stubs/view');

        $this->assertEquals("Application version: {$manager->formatted()}", view('stubs::app_version')->render());
        $this->assertEquals("Build version: {$manager->build()}", view('stubs::app_build')->render());
        $this->assertEquals(
            "Application version hash: {$manager->hashed()}, 16 = {$manager->hashed(16)}",
            view('stubs::app_version_hash')->render()
        );
    }
}
