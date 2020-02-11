<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Repository;

/**
 * @internal
 */
class Version
{
    /**
     * @var int|null
     */
    protected $major;

    /**
     * @var int|null
     */
    protected $minor;

    /**
     * @var int|null
     */
    protected $path;

    /**
     * @var string|null
     */
    protected $build;

    /**
     * Create a new version instance.
     *
     * @param int|null    $major
     * @param int|null    $minor
     * @param int|null    $path
     * @param string|null $build
     */
    public function __construct(?int $major = null, ?int $minor = null, ?int $path = null, ?string $build = null)
    {
        $this->major = $major;
        $this->minor = $minor;
        $this->path  = $path;
        $this->build = $build;
    }

    /**
     * @return int|null
     */
    public function getMajor(): ?int
    {
        return $this->major;
    }

    /**
     * @param int|null $major
     *
     * @return $this
     */
    public function setMajor(?int $major): self
    {
        $this->major = $major;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMinor(): ?int
    {
        return $this->minor;
    }

    /**
     * @param int|null $minor
     *
     * @return $this
     */
    public function setMinor(?int $minor): self
    {
        $this->minor = $minor;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPath(): ?int
    {
        return $this->path;
    }

    /**
     * @param int|null $path
     *
     * @return $this
     */
    public function setPath(?int $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBuild(): ?string
    {
        return $this->build;
    }

    /**
     * @param string|null $build
     *
     * @return $this
     */
    public function setBuild(?string $build): self
    {
        $this->build = $build;

        return $this;
    }
}
