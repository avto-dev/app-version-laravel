<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Drivers;

use Illuminate\Filesystem\Filesystem;
use AvtoDev\AppVersion\ServiceProvider;
use AvtoDev\AppVersion\Repositories\RepositoryInterface;
use AvtoDev\AppVersion\Repositories\ChangelogFileRepository;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class ChangelogFileDriver implements DriverInterface
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
     * Create a new changelog file repository driver.
     *
     * @param ConfigRepository $config
     * @param Filesystem       $filesystem
     */
    public function __construct(ConfigRepository $config, Filesystem $filesystem)
    {
        /** @var string|null $changelog_path */
        $changelog_path      = $config->get(ServiceProvider::getConfigRootKeyName() . '.changelog.path');
        $this->file_location = (string) $changelog_path;
        $this->filesystem    = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function createRepository(): RepositoryInterface
    {
        return new ChangelogFileRepository($this->file_location, $this->filesystem);
    }
}
