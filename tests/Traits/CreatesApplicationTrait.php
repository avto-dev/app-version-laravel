<?php

namespace AvtoDev\AppVersion\Tests\Traits;

use Illuminate\Contracts\Console\Kernel;
use AvtoDev\AppVersion\Tests\Bootstrap\TestsBootstraper;

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

        $app->useStoragePath(TestsBootstraper::getStorageDirectoryPath());

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
