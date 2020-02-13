<?php

namespace AvtoDev\AppVersion\Drivers;

use AvtoDev\AppVersion\Repositories\RepositoryInterface;

interface DriverInterface
{
    /**
     * Create repository instance.
     *
     * @return RepositoryInterface
     */
    public function __invoke(): RepositoryInterface;
}
