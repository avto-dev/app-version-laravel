<?php

namespace AvtoDev\AppVersion\Tests\Bootstrap;

use Illuminate\Contracts\Console\Kernel;

/**
 * Class TestsBootstraper.
 */
class TestsBootstraper extends AbstractTestsBootstraper
{
    /**
     * Stub bootstrap method.
     *
     * @return bool
     */
    protected function bootSomething()
    {
        return true;
    }
}
