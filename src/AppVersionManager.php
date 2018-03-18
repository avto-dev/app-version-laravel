<?php

namespace AvtoDev\AppVersion;

use Illuminate\Support\Str;
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
        'major'               => 0,
        'minor'               => 0,
        'patch'               => 0,
        'build'               => 0,
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
     * {@inheritdoc}
     */
    public function major()
    {
        return $this->config['major'];
    }

    /**
     * {@inheritdoc}
     */
    public function minor()
    {
        return $this->config['minor'];
    }

    /**
     * {@inheritdoc}
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
    public function refresh()
    {
        $this->clearCompiled();

        $this->setBuild($this->build());
        $this->setFormatted($this->formatted());
    }

    /**
     * {@inheritdoc}
     */
    public function setBuild($value)
    {
        $this->config['build'] = ($value = (string) $value);

        $result = $this->putIntoFile($this->config['build_metadata_path'], $value);

        $this->clearCompiled();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function formatted()
    {
        if ($this->files->exists($compiled_path = $this->config['compiled_path'])) {
            return $this->files->get($compiled_path);
        }

        $result = (string) str_replace(
            ['{{major}}', '{{minor}}', '{{patch}}', '{{build}}'],
            [$this->config['major'], $this->config['minor'], $this->config['patch'], $this->build()],
            $this->config['format']
        );

        $this->setFormatted($result);

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function version(...$arguments)
    {
        return $this->formatted(...$arguments);
    }

    /**
     * {@inheritdoc}
     */
    public function hashed($length = 6)
    {
        $length = (int) $length;
        $hash   = sha1($this->formatted());

        return Str::substr($hash, 0, $length > Str::length($hash)
            ? null
            : $length);
    }

    /**
     * Write the contents of a file.
     *
     * @param string $file_path
     * @param string $data
     *
     * @return bool
     */
    protected function putIntoFile($file_path, $data)
    {
        if ($this->files->isDirectory($this->files->dirname($file_path))) {
            $data = str_replace(["\n", "\r"], '', trim((string) $data));

            return $this->files->put($file_path, $data, $this->use_locking) > 0;
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
    protected function setFormatted($formatted)
    {
        return $this->putIntoFile($this->config['compiled_path'], $formatted);
    }
}
