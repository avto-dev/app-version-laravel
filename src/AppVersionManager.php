<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion;

use AvtoDev\AppVersion\Repositories\RepositoryInterface;

class AppVersionManager implements AppVersionManagerInterface
{
    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * Create a new version manager instance.
     *
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function version(): string
    {
        $version = \implode('.', [
            $this->repository->getMajor() ?? 0,
            $this->repository->getMinor() ?? 0,
            $this->repository->getPatch() ?? 0,
        ]);

        $build = $this->repository->getBuild();

        if (\is_string($build) && $build !== '') {
            $version .= "-{$build}";
        }

        return $version;
    }

    /**
     * {@inheritdoc}
     */
    public function formatted(string $format = '{major}.{minor}.{patch}-{build}'): string
    {
        return \str_replace(['{major}', '{minor}', '{patch}', '{build}'], [
            $this->repository->getMajor() ?? 0,
            $this->repository->getMinor() ?? 0,
            $this->repository->getPatch() ?? 0,
            $this->repository->getBuild() ?? '',
        ], $format);
    }

    /**
     * {@inheritdoc}
     */
    public function hashed(int $length = 6): string
    {
        $hash = \sha1($this->version());

        $new_length = $length > \mb_strlen($hash, 'UTF-8')
            ? null
            : $length;

        return \mb_substr($hash, 0, $new_length, 'UTF-8');
    }

    /**
     * {@inheritdoc}
     */
    public function repository(): RepositoryInterface
    {
        return $this->repository;
    }
}
