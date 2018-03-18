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

if (! function_exists('app_version_hash')) {
    /**
     * Returns hashed application version.
     *
     * @param int $length
     *
     * @return string
     */
    function app_version_hash($length = 6)
    {
        return resolve(AppVersionManagerContract::class)->hashed($length);
    }
}
