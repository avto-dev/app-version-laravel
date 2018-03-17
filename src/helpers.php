<?php

use AvtoDev\AppVersion\Contracts\AppVersionManagerContract;

if (! function_exists('app_version')) {
    /**
     * Returns application version.
     *
     * @return string
     */
    function app_version()
    {
        return resolve(AppVersionManagerContract::class)->formatted();
    }
}

if (! function_exists('app_build')) {
    /**
     * Returns application build (only) version.
     *
     * @return string
     */
    function app_build()
    {
        return resolve(AppVersionManagerContract::class)->build();
    }
}
