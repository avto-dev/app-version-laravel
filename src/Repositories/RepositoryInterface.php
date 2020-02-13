<?php

namespace AvtoDev\AppVersion\Repositories;

interface RepositoryInterface
{
    /**
     * Get major version value.
     *
     * @return int|null
     */
    public function getMajor(): ?int;

    /**
     * Set major version value.
     *
     * @param int $major
     *
     * @return void
     */
    public function setMajor(int $major): void;

    /**
     * Get minor version value.
     *
     * @return int|null
     */
    public function getMinor(): ?int;

    /**
     * Set minor version value.
     *
     * @param int $minor
     *
     * @return void
     */
    public function setMinor(int $minor): void;

    /**
     * Get patch version value.
     *
     * @return int|null
     */
    public function getPath(): ?int;

    /**
     * Set patch version value.
     *
     * @param int $path
     *
     * @return void
     */
    public function setPath(int $path): void;

    /**
     * Get build metadata value.
     *
     * @return string|null
     */
    public function getBuild(): ?string;

    /**
     * Set build metadata value.
     *
     * @param string $build
     *
     * @return void
     */
    public function setBuild(string $build): void;
}
