<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Tests;

use AvtoDev\AppVersion\AppVersionManagerInterface;

/**
 * @covers ::app_version
 * @covers ::app_build
 * @covers ::app_version_hash
 */
class HelpersTest extends AbstractTestCase
{
    /**
     * Test helpers.
     *
     * @return void
     */
    public function testHelpers(): void
    {
        ///** @var AppVersionManagerInterface $manager */
        //$manager = $this->app->make(AppVersionManagerInterface::class);
        //$manager->setBuild($build = 'pre-alpha2');
        //
        //$this->assertEquals("1.0.0-{$build}", app_version());
        //
        //$this->assertEquals($build, app_build());
        //
        //$this->assertEquals($manager->hashed(), app_version_hash());
        //$this->assertEquals($manager->hashed(16), app_version_hash(16));
    }
}
