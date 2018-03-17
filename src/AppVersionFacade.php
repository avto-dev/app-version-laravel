<?php

namespace AvtoDev\AppVersion;

use Illuminate\Support\Facades\Facade;
use AvtoDev\AppVersion\Contracts\AppVersionManagerContract;

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
