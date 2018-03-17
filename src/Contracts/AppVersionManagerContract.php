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
     * Returns formatted version value.
     *
     * @return string
     */
    public function formatted();
}
