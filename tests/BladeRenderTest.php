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

        $rendered = view('stubs::app_version')->render();

        $this->assertEquals("Application version: {$manager->formatted()}", $rendered);
    }
}
