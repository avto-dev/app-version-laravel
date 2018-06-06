<?php

namespace AvtoDev\AppVersion\Tests;

use AvtoDev\AppVersion\AppVersionServiceProvider;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class AbstractTestCase extends BaseTestCase
{
    use Traits\CreatesApplicationTrait;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->app->register(AppVersionServiceProvider::class);
    }
}
