<?php

namespace AvtoDev\AppVersion;

interface AppVersionManagerInterface
{
    /**
     * Get version repository.
     *
     * @return Repositories\RepositoryInterface
     */
    public function repository(): Repositories\RepositoryInterface;

    /**
     * Get hashed version value.
     *
     * @param int $length
     *
     * @return string
     */
    public function hashed(int $length = 6): string;

    /**
     * Get strict-formatted version value (e.g.: `1.0.0-beta+build.1`, `0.0.1`).
     *
     * Format: `{major}.{minor}.{path}[-{build_with_meta}]`
     *
     * @return string
     */
    public function version(): string;

    /**
     * Get version value using user-defined format. Allowed tokens: `{major}`, `{minor}`, `{path}` and `{build}`.
     *
     * @param string $format
     *
     * @return string
     */
    public function formatted(string $format = '{major}.{minor}.{path}-{build}'): string;
}
