<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Drivers;

use Illuminate\Filesystem\Filesystem;
use AvtoDev\AppVersion\ServiceProvider;
use AvtoDev\AppVersion\Repositories\RepositoryInterface;
use AvtoDev\AppVersion\Repositories\ConfigFileRepository;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class ConfigFileDriver implements DriverInterface
{
    /**
     * @var ConfigRepository
     */
    protected $config;

    /**
     * @var string
     */
    protected $build_file_location;

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
        $this->build_file_location = (string) $config->get(ServiceProvider::getConfigRootKeyName() . '.build_file');
        $this->config              = $config;
        $this->filesystem          = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(): RepositoryInterface
    {
        return new ConfigFileRepository($this->config, $this->build_file_location, $this->filesystem);
    }
}
