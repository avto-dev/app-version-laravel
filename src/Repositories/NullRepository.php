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
    protected $patch;

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
    public function getPatch(): ?int
    {
        return $this->patch;
    }

    /**
     * {@inheritdoc}
     */
    public function setPatch(int $patch): void
    {
        $this->patch = $patch;
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
