<?php

namespace AvtoDev\AppVersion\Drivers;

use AvtoDev\AppVersion\Repositories\RepositoryInterface;

/**
 * Dependencies injection using constructor is allowed in classes, that implements this interface.
 */
interface DriverInterface
{
    /**
     * Create repository instance.
     *
     * @return RepositoryInterface
     */
    public function createRepository(): RepositoryInterface;
}
