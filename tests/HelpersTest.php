<?php

namespace AvtoDev\AppVersion\Tests;

use AvtoDev\AppVersion\Contracts\AppVersionManagerContract;

/**
 * Class HelpersTest.
 */
class HelpersTest extends AbstractTestCase
{
    /**
     * Test helpers.
     *
     * @return void
     */
    public function testHelpers()
    {
        /** @var AppVersionManagerContract $manager */
        $manager = $this->app->make(AppVersionManagerContract::class);
        $manager->setBuild($build = 'pre-alpha2');

        $this->assertEquals("1.0.0-{$build}", app_version());

        $this->assertEquals("{$build}", app_build());
    }
}
