<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Repositories;

use InvalidArgumentException;
use Illuminate\Filesystem\Filesystem;
use AvtoDev\AppVersion\ServiceProvider;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class ConfigFileRepository implements RepositoryInterface
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
     * @var Filesystem|null
     */
    protected $file_system;

    /**
     * Create a new config repository instance.
     *
     * @param ConfigRepository $config
     * @param string           $build_file_location
     * @param Filesystem|null  $file_system
     */
    public function __construct(ConfigRepository $config, string $build_file_location, ?Filesystem $file_system = null)
    {
        $this->config              = $config;
        $this->build_file_location = $build_file_location;
        $this->file_system         = $file_system ?? new Filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function getMajor(): ?int
    {
        return \is_int($major = $this->config->get(ServiceProvider::getConfigRootKeyName() . '.major'))
            ? $major
            : null;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException Always
     */
    public function setMajor(int $major): void
    {
        throw new \RuntimeException('Current repository cannot set major value');
    }

    /**
     * {@inheritdoc}
     */
    public function getMinor(): ?int
    {
        return \is_int($minor = $this->config->get(ServiceProvider::getConfigRootKeyName() . '.minor'))
            ? $minor
            : null;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException Always
     */
    public function setMinor(int $minor): void
    {
        throw new \RuntimeException('Current repository cannot set minor value');
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(): ?int
    {
        return \is_int($patch = $this->config->get(ServiceProvider::getConfigRootKeyName() . '.patch'))
            ? $patch
            : null;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException Always
     */
    public function setPath(int $path): void
    {
        throw new \RuntimeException('Current repository cannot set path value');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException If file was not found
     */
    public function getBuild(): ?string
    {
        if ($this->file_system->exists($this->build_file_location)) {
            try {
                $content = \trim($this->file_system->get($this->build_file_location, true));
            } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $e) {
                throw new \RuntimeException("File does not exist at path [{$this->build_file_location}]");
            }

            if ($content !== '') {
                return $content;
            }
        }

        return \is_string($build = $this->config->get(ServiceProvider::getConfigRootKeyName() . '.build'))
            ? $build
            : null;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException         If file cannot be written
     * @throws \InvalidArgumentException If wrong build value passed
     */
    public function setBuild(string $build): void
    {
        // Validate build value
        if (\preg_match('~^[a-zA-Z0-9-.+]+$~', $build) !== 1) {
            throw new InvalidArgumentException("Wrong build value ({$build}) passed");
        }

        if ($this->file_system->put($this->build_file_location, $build, true) <= 0) {
            throw new \RuntimeException("File [{$this->build_file_location}] cannot be written");
        }
    }
}
