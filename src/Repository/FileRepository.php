<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Repository;

use InvalidArgumentException;
use Illuminate\Filesystem\Filesystem;
use AvtoDev\AppVersion\Support\Version;

class FileRepository implements RepositoryInterface
{
    /**
     * @var string
     */
    protected $file_location;

    /**
     * @var Filesystem
     */
    protected $file_system;

    /**
     * Create a new file repository instance.
     *
     * @param string     $file_location
     * @param Filesystem $file_system
     */
    public function __construct(string $file_location, ?Filesystem $file_system = null)
    {
        $this->file_location = $file_location;
        $this->file_system   = $file_system ?? new Filesystem;
    }

    /**
     * {@inheritDoc}
     */
    public function getMajor(): ?int
    {
        return $this->getVersionInfo()->getMajor();
    }

    /**
     * {@inheritDoc}
     */
    public function setMajor(int $major): void
    {
        $this->setVersionInfo($this->getVersionInfo()->setMajor($major));
    }

    /**
     * {@inheritDoc}
     */
    public function getMinor(): ?int
    {
        return $this->getVersionInfo()->getMinor();
    }

    /**
     * {@inheritDoc}
     */
    public function setMinor(int $minor): void
    {
        $this->setVersionInfo($this->getVersionInfo()->setMinor($minor));
    }

    /**
     * {@inheritDoc}
     */
    public function getPath(): ?int
    {
        return $this->getVersionInfo()->getPath();
    }

    /**
     * {@inheritDoc}
     */
    public function setPath(int $path): void
    {
        $this->setVersionInfo($this->getVersionInfo()->setPath($path));
    }

    /**
     * {@inheritDoc}
     */
    public function getBuild(): ?string
    {
        return $this->getVersionInfo()->getBuild();
    }

    /**
     * {@inheritDoc}
     */
    public function setBuild(string $build): void
    {
        $this->setVersionInfo($this->getVersionInfo()->setBuild($build));
    }

    /**
     * @throws \RuntimeException If file was not found
     *
     * @return Version
     */
    protected function getVersionInfo(): Version
    {
        try {
            $content = $this->file_system->get($this->file_location, true);
        } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $e) {
            throw new \RuntimeException("File does not exist at path [{$this->file_location}]");
        }

        return Version::parse($content);
    }

    /**
     * @param Version $version
     *
     * @throws \RuntimeException If file cannot be written
     * @throws \InvalidArgumentException If version is not valid
     *
     * @return void
     */
    protected function setVersionInfo(Version $version): void
    {
        $as_string = (string) $version;

        if ($version->isValid() !== true) {
            throw new InvalidArgumentException("Wrong version value ({$as_string}) cannot be written");
        }

        if ($this->file_system->put($this->file_location, $as_string, true) <= 0) {
            throw new \RuntimeException("File [{$this->file_location}] cannot be written");
        }
    }
}
