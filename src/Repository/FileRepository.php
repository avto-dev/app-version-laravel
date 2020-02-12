<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Repository;

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
     *
     * @throws \RuntimeException
     */
    public function getMajor(): ?int
    {
        return $this->getVersionInfo()->getMajor();
    }

    /**
     * {@inheritDoc}
     *
     * @throws \RuntimeException
     */
    public function setMajor(int $major): void
    {
        $this->setVersionInfo($this->getVersionInfo()->setMajor($major));
    }

    /**
     * {@inheritDoc}
     *
     * @throws \RuntimeException
     */
    public function getMinor(): ?int
    {
        return $this->getVersionInfo()->getMinor();
    }

    /**
     * {@inheritDoc}
     *
     * @throws \RuntimeException
     */
    public function setMinor(int $minor): void
    {
        $this->setVersionInfo($this->getVersionInfo()->setMinor($minor));
    }

    /**
     * {@inheritDoc}
     *
     * @throws \RuntimeException
     */
    public function getPath(): ?int
    {
        return $this->getVersionInfo()->getPath();
    }

    /**
     * {@inheritDoc}
     *
     * @throws \RuntimeException
     */
    public function setPath(int $path): void
    {
        $this->setVersionInfo($this->getVersionInfo()->setPath($path));
    }

    /**
     * {@inheritDoc}
     *
     * @throws \RuntimeException
     */
    public function getBuild(): ?string
    {
        return $this->getVersionInfo()->getBuild();
    }

    /**
     * {@inheritDoc}
     *
     * @throws \RuntimeException
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
     *
     * @return void
     */
    protected function setVersionInfo(Version $version): void
    {
        $value = \implode('.', [$version->getMajor() ?? 0, $version->getMinor() ?? 0, $version->getPath() ?? 0]);

        if ($version->getBuild() !== null) {
            $value .= "-{$version->getBuild()}";
        }

        if ($this->file_system->put($this->file_location, $value, true) <= 0) {
            throw new \RuntimeException("File [{$this->file_location}] cannot be written");
        }
    }
}
