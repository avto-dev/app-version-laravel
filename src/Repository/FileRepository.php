<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Repository;

use Illuminate\Filesystem\Filesystem;

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
        // @link <https://semver.org/#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string>
        static $regexp = '/^' .
                         '(?P<major>0|[1-9]\d*)\.' .
                         '(?P<minor>0|[1-9]\d*)\.' .
                         '(?P<patch>0|[1-9]\d*)(?:-' .
                         '(?P<pre>(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*)' .
                         '(?:\.(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))' .
                         '*))?(?:\+' .
                         '(?P<meta>[0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?$' .
                         '/m';

        try {
            \preg_match($regexp, $this->file_system->get($this->file_location, true), $matches);
        } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $e) {
            throw new \RuntimeException("File does not exist at path {$this->file_location}");
        }

        return new Version(
            isset($matches['major']) && \filter_var($matches['major'], \FILTER_VALIDATE_INT) !== false
                ? (int) $matches['major']
                : null,
            isset($matches['minor']) && \filter_var($matches['minor'], \FILTER_VALIDATE_INT) !== false
                ? (int) $matches['minor']
                : null,
            isset($matches['patch']) && \filter_var($matches['patch'], \FILTER_VALIDATE_INT) !== false
                ? (int) $matches['patch']
                : null,
            isset($matches['pre']) || isset($matches['meta'])
                ? \implode('+', \array_filter([$matches['pre'] ?? null, $matches['meta'] ?? null], '\\is_string'))
                : null
        );
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
            throw new \RuntimeException("File {$this->file_location} cannot be written");
        }
    }
}
