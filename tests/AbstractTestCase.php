<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Tests;

abstract class AbstractTestCase extends \Illuminate\Foundation\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        /** @var \Illuminate\Foundation\Application $app */
        $app = require __DIR__ . '/../vendor/laravel/laravel/bootstrap/app.php';

        $app->useStoragePath(__DIR__ . '/temp/storage');
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        $app->register(\AvtoDev\AppVersion\ServiceProvider::class);

        return $app;
    }
}
