<?php

namespace AvtoDev\AppVersion;

interface AppVersionManagerInterface
{
    /**
     * Get version repository.
     *
     * @return Repository\RepositoryInterface
     */
    public function repository(): Repository\RepositoryInterface;

    /**
     * Get hashed version value.
     *
     * @param int $length
     *
     * @return string
     */
    public function hashed(int $length = 6): string;

    /**
     * Get formatted version value (e.g.: `1.0.0-beta+build.1`, `0.0.1`).
     *
     * Format: `{major}.{minor}.{path}[-{build_with_meta}]`
     *
     * @return string
     */
    public function version(): string;
}
