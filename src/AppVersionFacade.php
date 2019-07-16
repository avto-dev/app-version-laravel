<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion;

use AvtoDev\AppVersion\Contracts\AppVersionManagerContract;

class AppVersionFacade extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return AppVersionManagerContract::class;
    }
}
