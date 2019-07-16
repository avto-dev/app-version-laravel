<?php

namespace AvtoDev\AppVersion\Contracts;

interface AppVersionManagerContract
{
    /**
     * Get major version value.
     *
     * @return int|null
     */
    public function major(): ?int;

    /**
     * Get minor version value.
     *
     * @return int|null
     */
    public function minor(): ?int;

    /**
     * Get patch version value.
     *
     * @return int|null
     */
    public function patch(): ?int;

    /**
     * Get build metadata value.
     *
     * @return string|null
     */
    public function build(): ?string;

    /**
     * Refresh values, stored in files (recreate files if needed).
     *
     * @return void
     */
    public function refresh(): void;

    /**
     * Set build metadata value and store it onto file.
     *
     * @param string $value
     *
     * @return void
     */
    public function setBuild(string $value): void;

    /**
     * Get hashed version value.
     *
     * @param int $length
     *
     * @return string
     */
    public function hashed(int $length = 6): string;

    /**
     * Returns formatted version value.
     *
     * @return string
     */
    public function formatted(): string;

    /**
     * Returns formatted version value (alias for `formatted()` method).
     *
     * @return string
     */
    public function version(): string;
}
