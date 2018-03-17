<?php

namespace AvtoDev\AppVersion\Tests\Commands;

use Illuminate\Contracts\Console\Kernel;
use AvtoDev\AppVersion\Tests\AbstractTestCase;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;

/**
 * Class AbstractCommandTestCase.
 */
abstract class AbstractCommandTestCase extends AbstractTestCase
{
    /**
     * Basic command test.
     *
     * @return void
     */
    public function testHelpCommand()
    {
        $this->assertNotFalse(
            $this->artisan($signature = $this->getCommandSignature(), ['--help']),
            sprintf('Command "%s" does not return help message', $signature)
        );
    }

    /**
     * @return Kernel|\App\Console\Kernel
     */
    public function console()
    {
        return $this->app->make(Kernel::class);
    }

    /**
     * Command signature.
     *
     * @return string
     */
    abstract protected function getCommandSignature();
}
