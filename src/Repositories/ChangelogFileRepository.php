<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Repositories;

use Illuminate\Filesystem\Filesystem;
use AvtoDev\AppVersion\Support\Version;

class ChangelogFileRepository implements RepositoryInterface
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
     * Create a new changelog file repository instance.
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
     * {@inheritdoc}
     */
    public function getMajor(): ?int
    {
        return $this->getVersionInfo()->getMajor();
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
        return $this->getVersionInfo()->getMinor();
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
    public function getPatch(): ?int
    {
        return $this->getVersionInfo()->getPatch();
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException Always
     */
    public function setPatch(int $patch): void
    {
        throw new \RuntimeException('Current repository cannot set patch value');
    }

    /**
     * {@inheritdoc}
     */
    public function getBuild(): ?string
    {
        return $this->getVersionInfo()->getBuild();
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException Always
     */
    public function setBuild(string $build): void
    {
        throw new \RuntimeException('Current repository cannot set build value');
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

        \preg_match_all('~^##[\\s\\[]+[vV]?(?P<version>\\d+\\.\\d+\\.\\d+[a-zA-Z0-9-.+]*).*$~m', $content, $matches);

        if (isset($matches['version'][0])) {
            return Version::parse($matches['version'][0]);
        }

        throw new \RuntimeException('Cannot extract latest version header from changelog file');
    }
}
