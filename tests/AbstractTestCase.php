<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Tests;

use AvtoDev\AppVersion\ServiceProvider;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class AbstractTestCase extends BaseTestCase
{
    use Traits\CreatesApplicationTrait;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->register(ServiceProvider::class);
    }
}
