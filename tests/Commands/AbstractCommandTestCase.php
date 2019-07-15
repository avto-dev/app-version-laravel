<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Tests\Commands;

use Illuminate\Contracts\Console\Kernel;
use AvtoDev\AppVersion\Tests\AbstractTestCase;

abstract class AbstractCommandTestCase extends AbstractTestCase
{
    /**
     * Indicates if the console output should be mocked.
     *
     * @var bool
     */
    public $mockConsoleOutput = false;

    /**
     * Basic command test.
     *
     * @return void
     */
    public function testHelpCommand(): void
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
    abstract protected function getCommandSignature(): string;
}
