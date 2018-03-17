<?php

namespace AvtoDev\AppVersion\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Class AbstractTestCase.
 */
abstract class AbstractTestCase extends BaseTestCase
{
    use Traits\CreatesApplicationTrait,
        Traits\AdditionalAssertsTrait;
}
