<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use AvtoDev\AppVersion\Contracts\AppVersionManagerContract;

class AppVersionManager implements AppVersionManagerContract
{
    /**
     * Configuration.
     *
     * @var array
     */
    protected $config = [
        'major'               => 0,
        'minor'               => 0,
        'patch'               => 0,
        'build'               => '0',
        'format'              => '{{major}}.{{minor}}.{{patch}}-{{build}}',
        'compiled_path'       => null,
        'build_metadata_path' => null,
    ];

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * Use files locking.
     *
     * @var bool
     */
    protected $use_locking = true;

    /**
     * AppVersionManager constructor.
     *
     * @param array $config
     * @param bool  $use_locking
     */
    public function __construct(array $config = [], $use_locking = true)
    {
        $this->config = (array) \array_replace_recursive($this->config, $config);

        // Make minimalistic normalization/typing for versions values
        foreach (['major', 'minor', 'patch'] as $key) {
            if (\is_int($value = $this->config[$key])) {
                if ($value < 0) {
                    $this->config[$key] = 0;
                }
            } else {
                $this->config[$key] = (int) \preg_replace('/\D/', '', (string) $value);
            }
        }

        if (isset($config['build'])) {
            $this->config['build'] = (string) $config['build'];
        }

        $this->use_locking = (bool) $use_locking;
        $this->files       = new Filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function major(): int
    {
        return (int) $this->config['major'];
    }

    /**
     * {@inheritdoc}
     */
    public function minor(): int
    {
        return (int) $this->config['minor'];
    }

    /**
     * {@inheritdoc}
     */
    public function patch(): int
    {
        return (int) $this->config['patch'];
    }

    /**
     * {@inheritdoc}
     */
    public function build(): string
    {
        $from_file = $this->buildStored();

        if (\is_string($from_file) && ! empty($from_file)) {
            return $from_file;
        }

        return (string) $this->config['build'];
    }

    /**
     * {@inheritdoc}
     */
    public function setBuild(string $value): void
    {
        if ($this->config['build'] !== $value && $this->buildStored() !== $value) {
            $this->putIntoFile($this->config['build_metadata_path'], $value);
            $this->setFormatted($this->formatted());
        }

        $this->config['build'] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function refresh(): void
    {
        $this->setBuild($this->build());

        $this->forgetFormatted();
        $this->setFormatted($this->formatted());
    }

    /**
     * {@inheritdoc}
     */
    public function formatted(): string
    {
        return \str_replace(
            ['{{major}}', '{{minor}}', '{{patch}}', '{{build}}'],
            [$this->config['major'], $this->config['minor'], $this->config['patch'], $this->build()],
            $this->config['format']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function version(): string
    {
        return $this->formatted();
    }

    /**
     * {@inheritdoc}
     */
    public function hashed(int $length = 6): string
    {
        $hash = \sha1($this->formatted());

        return Str::substr($hash, 0, $length > Str::length($hash)
            ? null
            : $length);
    }

    /**
     * Get build value from metadata file.
     *
     * @return null|string
     */
    protected function buildStored(): ?string
    {
        return $this->files->exists($file_path = $this->config['build_metadata_path'])
            ? $this->files->get($file_path, $this->use_locking)
            : null;
    }

    /**
     * Forget stored formatted value.
     *
     * @return bool
     */
    protected function forgetFormatted(): bool
    {
        if ($this->files->exists($compiled_path = $this->config['compiled_path'])) {
            return $this->files->delete($compiled_path);
        }

        return false;
    }

    /**
     * Set formatted version value and store it onto file.
     *
     * @param string $formatted
     *
     * @return bool
     */
    protected function setFormatted($formatted): bool
    {
        return $this->putIntoFile($this->config['compiled_path'], $formatted);
    }

    /**
     * Write the contents of a file.
     *
     * @param string $file_path
     * @param string $data
     *
     * @return bool
     */
    protected function putIntoFile($file_path, $data): bool
    {
        if ($this->files->isDirectory($this->files->dirname($file_path))) {
            $data = \str_replace(["\n", "\r"], '', \trim((string) $data));

            return $this->files->put($file_path, $data, $this->use_locking) > 0;
        }

        // @codeCoverageIgnoreStart
        return false;
        // @codeCoverageIgnoreEnd
    }
}
