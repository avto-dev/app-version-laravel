<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Tests\Traits;

use Illuminate\Contracts\Console\Kernel;

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

        $app->useStoragePath(__DIR__ . '/../temp/storage');

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
