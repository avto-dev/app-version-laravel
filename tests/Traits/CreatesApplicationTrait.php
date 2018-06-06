<?php

namespace AvtoDev\AppVersion\Tests\Traits;

use AvtoDev\AppVersion\AppVersionServiceProvider;
use Illuminate\Contracts\Console\Kernel;
use AvtoDev\AppVersion\Tests\Bootstrap\TestsBootstrapper;

trait CreatesApplicationTrait
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        /** @var \Illuminate\Foundation\Application $app */
        $app = require __DIR__ . '/../../vendor/laravel/laravel/bootstrap/app.php';

        $app->useStoragePath(TestsBootstrapper::getStorageDirectoryPath());

        $app->make(Kernel::class)->bootstrap();

        $app->register(AppVersionServiceProvider::class);

        return $app;
    }
}
