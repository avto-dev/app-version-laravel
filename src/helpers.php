<?php

declare(strict_types = 1);

use Illuminate\Container\Container;
use AvtoDev\AppVersion\Contracts\AppVersionManagerContract;

if (! \function_exists('app_version')) {
    /**
     * Returns application version.
     *
     * @return string
     */
    function app_version(): string
    {
        return Container::getInstance()->make(AppVersionManagerContract::class)->formatted();
    }
}

if (! \function_exists('app_build')) {
    /**
     * Returns application build (only) version.
     *
     * @return string|null
     */
    function app_build(): ?string
    {
        return Container::getInstance()->make(AppVersionManagerContract::class)->build();
    }
}

if (! \function_exists('app_version_hash')) {
    /**
     * Returns hashed application version.
     *
     * @param int $length
     *
     * @return string
     */
    function app_version_hash($length = 6): string
    {
        return Container::getInstance()->make(AppVersionManagerContract::class)->hashed($length);
    }
}
