<?php

declare(strict_types = 1);

use Illuminate\Container\Container;
use AvtoDev\AppVersion\AppVersionManagerInterface;

if (! \function_exists('app_version')) {
    /**
     * Get formatted version value (e.g.: `1.0.0-beta+build.1`, `0.0.1`).
     *
     * Format: `{major}.{minor}.{path}[-{build_with_meta}]`
     *
     * @return string
     */
    function app_version(): string
    {
        /** @var AppVersionManagerInterface $manager */
        $manager = Container::getInstance()->make(AppVersionManagerInterface::class);

        return $manager->version();
    }
}

if (! \function_exists('app_build')) {
    /**
     * Get build metadata (only) value.
     *
     * @return string|null
     */
    function app_build(): ?string
    {
        /** @var AppVersionManagerInterface $manager */
        $manager = Container::getInstance()->make(AppVersionManagerInterface::class);

        return $manager->repository()->getBuild();
    }
}

if (! \function_exists('app_version_hash')) {
    /**
     * Get hashed version value.
     *
     * @param int $length
     *
     * @return string
     */
    function app_version_hash($length = 6): string
    {
        /** @var AppVersionManagerInterface $manager */
        $manager = Container::getInstance()->make(AppVersionManagerInterface::class);

        return $manager->hashed($length);
    }
}
