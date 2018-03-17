<?php

namespace AvtoDev\AppVersion;

use Illuminate\Contracts\Foundation\Application;
use AvtoDev\AppVersion\Contracts\AppVersionRepositoryContract;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

/**
 * Class AppVersionServiceProvider.
 */
class AppVersionServiceProvider extends IlluminateServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Get config root key name.
     *
     * @return string
     */
    public static function getConfigRootKeyName()
    {
        return basename(static::getConfigPath(), '.php');
    }

    /**
     * Returns path to the configuration file.
     *
     * @return string
     */
    public static function getConfigPath()
    {
        return env('APP_VERSION_CONFIG_PATH', __DIR__ . '/config/version.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->initializeConfigs();

        $this->registerQueueManager();
    }

    /**
     * Register queues manager.
     *
     * @return void
     */
    protected function registerQueueManager()
    {
        $this->app->singleton(AppVersionRepository::class, function (Application $app) {
            $config = (array) $app
                ->make('config')
                ->get(static::getConfigRootKeyName());

            return new AppVersionRepository($config);
        });

        $this->app->bind(AppVersionRepositoryContract::class, AppVersionRepository::class);
    }

    /**
     * Initialize configs.
     *
     * @return void
     */
    protected function initializeConfigs()
    {
        $this->mergeConfigFrom(static::getConfigPath(), static::getConfigRootKeyName());

        $this->publishes([
            realpath(static::getConfigPath()) => config_path(basename(static::getConfigPath())),
        ], 'config');
    }
}
