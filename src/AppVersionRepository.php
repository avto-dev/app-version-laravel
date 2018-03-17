<?php

namespace AvtoDev\AppVersion;

use AvtoDev\AppVersion\Contracts\AppVersionRepositoryContract;

/**
 * Class AppVersionRepository.
 */
class AppVersionRepository implements AppVersionRepositoryContract
{
    /**
     * Configuration.
     *
     * @var array
     */
    protected $config = [
        'major'         => null,
        'minor'         => null,
        'patch'         => null,
        'build'         => null,
        'format'        => null,
        'compiled_path' => null,
    ];

    /**
     * AppVersionRepository constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = array_replace_recursive($this->config, $config);
    }
}
