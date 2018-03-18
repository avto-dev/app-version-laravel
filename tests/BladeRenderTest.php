<?php

namespace AvtoDev\AppVersion\Tests;

use AvtoDev\AppVersion\Contracts\AppVersionManagerContract;

/**
 * Class BladeRenderTest.
 */
class BladeRenderTest extends AbstractTestCase
{
    /**
     * Rendering test.
     *
     * @return void
     */
    public function testRendering()
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
