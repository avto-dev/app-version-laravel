<?php

namespace AvtoDev\AppVersion\Repository;

interface RepositoryInterface
{
    /**
     * @return int|null
     */
    public function getMajor(): ?int;

    /**
     * @param int $major
     *
     * @throws \Exception
     *
     * @return void
     */
    public function setMajor(int $major): void;

    /**
     * @return int|null
     */
    public function getMinor(): ?int;

    /**
     * @param int $minor
     *
     * @throws \Exception
     *
     * @return void
     */
    public function setMinor(int $minor): void;

    /**
     * @return int|null
     */
    public function getPath(): ?int;

    /**
     * @param int $path
     *
     * @throws \Exception
     *
     * @return void
     */
    public function setPath(int $path): void;

    /**
     * @return string|null
     */
    public function getBuild(): ?string;

    /**
     * @param string $build
     *
     * @throws \Exception
     *
     * @return void
     */
    public function setBuild(string $build): void;
}
