<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Tests;

use Illuminate\Support\Str;
use AvtoDev\AppVersion\AppVersionManager;
use AvtoDev\AppVersion\Repositories\NullRepository;
use AvtoDev\AppVersion\AppVersionManagerInterface as ManagerContract;

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

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        $app->register(\AvtoDev\AppVersion\ServiceProvider::class);

        return $app;
    }

    /**
     * Override version manager in application containers and get new manager instance.
     *
     * @return AppVersionManager
     */
    protected function overrideVersionManager(): AppVersionManager
    {
        $manager = new AppVersionManager(new NullRepository);

        $this->app->extend(ManagerContract::class, function (AppVersionManager $_) use ($manager): ManagerContract {
            return $manager;
        });

        return $manager;
    }

    /**
     * Set random version value for manager repository and return new version object.
     *
     * @param AppVersionManager|null $manager
     *
     * @return void
     */
    protected function setRandomVersion(?AppVersionManager $manager = null): void
    {
        $manager    = $manager ?? $this->app->make(ManagerContract::class);
        $repository = $manager->repository();

        $repository->setMajor(\random_int(1, 20));
        $repository->setMinor(\random_int(21, 40));
        $repository->setPatch(\random_int(41, 60));
        $repository->setBuild(Str::random());
    }
}
