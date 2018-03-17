<?php

namespace AvtoDev\AppVersion\Tests;

use AvtoDev\AppVersion\AppVersionFacade;
use AvtoDev\AppVersion\AppVersionManager;
use AvtoDev\AppVersion\AppVersionServiceProvider;
use AvtoDev\AppVersion\Contracts\AppVersionManagerContract;

/**
 * Class AppVersionServiceProviderTest.
 */
class AppVersionServiceProviderTest extends AbstractTestCase
{
    /**
     * Test Laravel DI.
     *
     * @return void
     */
    public function testDI()
    {
        $manager = $this->app->make(AppVersionManagerContract::class);

        $this->assertInstanceOf(AppVersionManagerContract::class, $manager);
        $this->assertInstanceOf(AppVersionManager::class, $manager);

        $this->assertInstanceOf(AppVersionManager::class, $this->app->make('app.version.manager'));

        $this->assertInstanceOf(AppVersionManager::class, AppVersionFacade::shouldReceive('true')->getMock());
    }

    /**
     * Test service provider configs.
     *
     * @return void
     */
    public function testConfigs()
    {
        $this->assertFileExists($path = AppVersionServiceProvider::getConfigPath());
        $this->assertEquals(AppVersionServiceProvider::getConfigRootKeyName(), $base = basename($path, '.php'));

        foreach (array_dot($configs = require $path) as $key => $value) {
            $this->assertEquals($this->app->make('config')->get($base . '.' . $key), $value);
        }

        foreach (['major', 'minor', 'patch'] as $key) {
            $this->assertArrayHasKey($key, $configs);
            $this->assertTrue(is_int($configs[$key]));
        }

        foreach (['{{major}}', '{{minor}}', '{{patch}}', '{{build}}'] as $item) {
            $this->assertContains($item, $configs['format']);
        }
    }
}
