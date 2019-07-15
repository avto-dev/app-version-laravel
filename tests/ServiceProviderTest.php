<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Tests;

use Illuminate\Support\Arr;
use AvtoDev\AppVersion\ServiceProvider;
use AvtoDev\AppVersion\AppVersionFacade;
use AvtoDev\AppVersion\AppVersionManager;
use AvtoDev\AppVersion\Contracts\AppVersionManagerContract;

class ServiceProviderTest extends AbstractTestCase
{
    /**
     * Test Laravel DI.
     *
     * @return void
     */
    public function testDI(): void
    {
        $manager = $this->app->make(AppVersionManagerContract::class);

        $this->assertInstanceOf(AppVersionManagerContract::class, $manager);
        $this->assertInstanceOf(AppVersionManager::class, $manager);

        $this->assertInstanceOf(AppVersionManager::class, AppVersionFacade::shouldReceive('true')->getMock());
    }

    /**
     * Test service provider configs.
     *
     * @return void
     */
    public function testConfigs(): void
    {
        $this->assertFileExists($path = ServiceProvider::getConfigPath());
        $this->assertEquals(ServiceProvider::getConfigRootKeyName(), $base = basename($path, '.php'));

        foreach (Arr::dot($configs = require $path) as $key => $value) {
            $this->assertEquals($this->app->make('config')->get($base . '.' . $key), $value);
        }

        foreach (['major', 'minor', 'patch'] as $key) {
            $this->assertArrayHasKey($key, $configs);
            $this->assertInternalType('integer', $configs[$key]);
        }

        foreach (['{{major}}', '{{minor}}', '{{patch}}', '{{build}}'] as $item) {
            $this->assertContains($item, $configs['format']);
        }
    }
}
