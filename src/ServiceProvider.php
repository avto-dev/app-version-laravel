<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion;

use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Contracts\Container\Container;
use AvtoDev\AppVersion\Contracts\AppVersionManagerContract;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Get config root key name.
     *
     * @return string
     */
    public static function getConfigRootKeyName(): string
    {
        return \basename(static::getConfigPath(), '.php');
    }

    /**
     * Returns path to the configuration file.
     *
     * @return string
     */
    public static function getConfigPath(): string
    {
        return env('APP_VERSION_CONFIG_PATH', __DIR__ . '/config/version.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
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
    protected function registerAppVersionManager(): void
    {
        $this->app->singleton(AppVersionManager::class, function (Container $app) {
            $config = (array) $app
                ->make('config')
                ->get(static::getConfigRootKeyName());

            return new AppVersionManager($config);
        });

        $this->app->bind(AppVersionManagerContract::class, AppVersionManager::class);
    }

    /**
     * Register Blade directives.
     *
     * @return void
     */
    protected function registerBlade(): void
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $blade) {
            $blade->directive('app_version', function (): string {
                return "<?php echo resolve('" . AppVersionManagerContract::class . "')->formatted(); ?>";
            });

            $blade->directive('app_build', function (): string {
                return "<?php echo resolve('" . AppVersionManagerContract::class . "')->build(); ?>";
            });

            $blade->directive('app_version_hash', function ($length = 6): string {
                return "<?php echo resolve('" . AppVersionManagerContract::class . "')->hashed({$length}); ?>";
            });
        });
    }

    /**
     * Register package helpers.
     *
     * @return void
     */
    protected function registerHelpers(): void
    {
        require_once __DIR__ . '/helpers.php';
    }

    /**
     * Initialize configs.
     *
     * @return void
     */
    protected function initializeConfigs(): void
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
    protected function registerArtisanCommands(): void
    {
        $this->commands([
            Commands\VersionCommand::class,
        ]);
    }
}
