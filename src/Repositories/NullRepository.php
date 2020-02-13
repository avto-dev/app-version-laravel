<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Repositories;

class NullRepository implements RepositoryInterface
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
     * @inheritDoc
     */
    public function getMajor(): ?int
    {
        return $this->major;
    }

    /**
     * @inheritDoc
     */
    public function setMajor(int $major): void
    {
        $this->major = $major;
    }

    /**
     * @inheritDoc
     */
    public function getMinor(): ?int
    {
        return $this->minor;
    }

    /**
     * @inheritDoc
     */
    public function setMinor(int $minor): void
    {
        $this->minor = $minor;
    }

    /**
     * @inheritDoc
     */
    public function getPath(): ?int
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function setPath(int $path): void
    {
        $this->path = $path;
    }

    /**
     * @inheritDoc
     */
    public function getBuild(): ?string
    {
        return $this->build;
    }

    /**
     * @inheritDoc
     */
    public function setBuild(string $build): void
    {
        $this->build = $build;
    }
}
