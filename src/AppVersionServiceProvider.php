<?php

namespace AvtoDev\AppVersion;

use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Contracts\Foundation\Application;
use AvtoDev\AppVersion\Contracts\AppVersionManagerContract;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class AppVersionServiceProvider extends IlluminateServiceProvider
{
    /**
     * Versions manager DI bind alias.
     */
    const VERSION_MANAGER_ALIAS = 'app.version.manager';

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

        $this->registerAppVersionManager();

        $this->registerHelpers();

        $this->registerBlade();

        if ($this->app->runningInConsole()) {
            $this->registerArtisanCommands();
        }
    }

    /**
     * Register version manager instance.
     *
     * @return void
     */
    protected function registerAppVersionManager()
    {
        $this->app->singleton(AppVersionManager::class, function (Application $app) {
            $config = (array) $app
                ->make('config')
                ->get(static::getConfigRootKeyName());

            return new AppVersionManager($config);
        });

        $this->app->bind(AppVersionManagerContract::class, AppVersionManager::class);
        $this->app->bind(static::VERSION_MANAGER_ALIAS, AppVersionManagerContract::class);
    }

    /**
     * Register Blade directives.
     */
    protected function registerBlade()
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $blade) {
            $blade->directive('app_version', function () {
                return "<?php echo resolve('" . AppVersionManagerContract::class . "')->formatted(); ?>";
            });

            $blade->directive('app_build', function () {
                return "<?php echo resolve('" . AppVersionManagerContract::class . "')->build(); ?>";
            });

            $blade->directive('app_version_hash', function ($length = 6) {
                return "<?php echo resolve('" . AppVersionManagerContract::class . "')->hashed({$length}); ?>";
            });
        });
    }

    /**
     * Register package helpers.
     *
     * @return void
     */
    protected function registerHelpers()
    {
        require_once __DIR__ . '/helpers.php';
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

    /**
     * Register artisan commands.
     *
     * @return void
     */
    protected function registerArtisanCommands()
    {
        $this->commands([
            Commands\VersionCommand::class,
        ]);
    }
}
