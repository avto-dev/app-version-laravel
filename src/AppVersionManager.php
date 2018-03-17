<?php

namespace AvtoDev\AppVersion;

use Illuminate\Filesystem\Filesystem;
use AvtoDev\AppVersion\Contracts\AppVersionManagerContract;

/**
 * Class AppVersionManager.
 */
class AppVersionManager implements AppVersionManagerContract
{
    /**
     * Configuration.
     *
     * @var array
     */
    protected $config = [
        'major'               => null,
        'minor'               => null,
        'patch'               => null,
        'build'               => null,
        'format'              => null,
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
    protected $use_locking = false;

    /**
     * AppVersionManager constructor.
     *
     * @param array $config
     * @param bool  $use_locking
     */
    public function __construct(array $config, $use_locking = true)
    {
        $this->config = array_replace_recursive($this->config, $config);

        // Make minimalistic normalization for versions values
        foreach (['major', 'minor', 'patch'] as $key) {
            if (is_int($value = $this->config[$key])) {
                if ($value < 0) {
                    $this->config[$key] = 0;
                }
            } else {
                $this->config[$key] = (int) preg_replace('/[^0-9]/', '', (string) $value);
            }
        }

        $this->use_locking = (bool) $use_locking;
        $this->files       = new Filesystem;
    }

    /**
     * Get major version value.
     *
     * @return int|null
     */
    public function major()
    {
        return $this->config['major'];
    }

    /**
     * Get minor version value.
     *
     * @return int|null
     */
    public function minor()
    {
        return $this->config['minor'];
    }

    /**
     * Get patch version value.
     *
     * @return int|null
     */
    public function patch()
    {
        return $this->config['patch'];
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        return $this->files->exists($build_metadata_path = $this->config['build_metadata_path'])
            ? $this->files->get($build_metadata_path)
            : $this->config['build'];
    }

    /**
     * {@inheritdoc}
     */
    public function clearCompiled()
    {
        $this->files->delete($this->config['compiled_path']);
    }

    /**
     * {@inheritdoc}
     */
    public function setBuild($value)
    {
        $result                = false;
        $this->config['build'] = ($value = (string) $value);

        if ($this->files->isDirectory($this->files->dirname($file_path = $this->config['build_metadata_path']))) {
            $result = $this->files->put($this->config['build_metadata_path'], $value, $this->use_locking) > 0;
        }

        $this->clearCompiled();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function formatted($format = null)
    {
        if ($this->files->exists($compiled_path = $this->config['compiled_path'])) {
            return $this->files->get($compiled_path);
        }

        $format = (is_string($format) && ! empty($format))
            ? $format
            : $this->config['format'];

        $result = (string) str_replace(
            ['{{major}}', '{{minor}}', '{{patch}}', '{{build}}'],
            [$this->config['major'], $this->config['minor'], $this->config['patch'], $this->build()],
            $format
        );

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function setFormatted($formatted)
    {
        if ($this->files->isDirectory($this->files->dirname($file_path = $this->config['compiled_path']))) {
            return $this->files->put($this->config['compiled_path'], (string) $formatted, $this->use_locking) > 0;
        }

        return false;
    }
}
