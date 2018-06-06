<?php

namespace AvtoDev\AppVersion\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class AbstractTestCase extends BaseTestCase
{
    use Traits\CreatesApplicationTrait;
}
