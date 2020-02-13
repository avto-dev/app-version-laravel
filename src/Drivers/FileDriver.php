<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Drivers;

use Illuminate\Filesystem\Filesystem;
use AvtoDev\AppVersion\ServiceProvider;
use AvtoDev\AppVersion\Repositories\FileRepository;
use AvtoDev\AppVersion\Repositories\RepositoryInterface;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class FileDriver implements DriverInterface
{
    /**
     * @var string
     */
    protected $file_location;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Create a new file repository driver.
     *
     * @param ConfigRepository $config
     * @param Filesystem       $filesystem
     */
    public function __construct(ConfigRepository $config, Filesystem $filesystem)
    {
        $this->file_location = (string) $config->get(ServiceProvider::getConfigRootKeyName() . '.version_file');
        $this->filesystem    = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function createRepository(): RepositoryInterface
    {
        return new FileRepository($this->file_location, $this->filesystem);
    }
}
