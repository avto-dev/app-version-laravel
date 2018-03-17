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
     * @param null|string $format
     *
     * @return string
     */
    public function formatted($format = null);

    /**
     * Set formatted version value and store it onto file.
     *
     * @param string $formatted
     *
     * @return bool
     */
    public function setFormatted($formatted);
}
