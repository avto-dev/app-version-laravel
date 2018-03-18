<?php

namespace AvtoDev\AppVersion\Contracts;

/**
 * Interface AppVersionManagerContract.
 */
interface AppVersionManagerContract
{
    /**
     * Get major version value.
     *
     * @return int|null
     */
    public function major();

    /**
     * Get minor version value.
     *
     * @return int|null
     */
    public function minor();

    /**
     * Get patch version value.
     *
     * @return int|null
     */
    public function patch();

    /**
     * Get build metadata value.
     *
     * @return string|null
     */
    public function build();

    /**
     * Remove compiled file.
     *
     * @return void
     */
    public function clearCompiled();

    /**
     * Put values into files.
     *
     * @return void
     */
    public function refresh();

    /**
     * Set build metadata value and store it onto file.
     *
     * @param string $value
     *
     * @return bool
     */
    public function setBuild($value);

    /**
     * Get hashed version value.
     *
     * @param int $length
     *
     * @return string
     */
    public function hashed($length = 6);

    /**
     * Returns formatted version value.
     *
     * @return string
     */
    public function formatted();

    /**
     * Returns formatted version value (alias for `formatted()` method).
     *
     * @return string
     */
    public function version();
}
