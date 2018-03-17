<?php

namespace AvtoDev\AppVersion;

use AvtoDev\AppVersion\Contracts\AppVersionManagerContract;
use Illuminate\Support\Facades\Facade;

/**
 * Class AppVersionFacade.
 */
class AppVersionFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return AppVersionManagerContract::class;
    }
}
