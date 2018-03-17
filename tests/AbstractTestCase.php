<?php

namespace AvtoDev\AppVersion\Tests;

use AvtoDev\AppVersion\AppVersionServiceProvider;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Class AbstractTestCase.
 */
abstract class AbstractTestCase extends BaseTestCase
{
    use Traits\CreatesApplicationTrait,
        Traits\AdditionalAssertsTrait;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->app->register(AppVersionServiceProvider::class);
    }
}
