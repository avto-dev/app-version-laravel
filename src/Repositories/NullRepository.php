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
     * {@inheritdoc}
     */
    public function getMajor(): ?int
    {
        return $this->major;
    }

    /**
     * {@inheritdoc}
     */
    public function setMajor(int $major): void
    {
        $this->major = $major;
    }

    /**
     * {@inheritdoc}
     */
    public function getMinor(): ?int
    {
        return $this->minor;
    }

    /**
     * {@inheritdoc}
     */
    public function setMinor(int $minor): void
    {
        $this->minor = $minor;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(): ?int
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function setPath(int $path): void
    {
        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     */
    public function getBuild(): ?string
    {
        return $this->build;
    }

    /**
     * {@inheritdoc}
     */
    public function setBuild(string $build): void
    {
        $this->build = $build;
    }
}
