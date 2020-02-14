<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion;

use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Contracts\Container\Container;
use AvtoDev\AppVersion\Drivers\DriverInterface;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use AvtoDev\AppVersion\AppVersionManagerInterface as ManagerInterface;

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
        return __DIR__ . '/../config/version.php';
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->initializeConfigs();

        $this->registerManager();
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
    protected function registerManager(): void
    {
        $this->app->singleton(ManagerInterface::class, static function (Container $app): ManagerInterface {
            /** @var ConfigRepository $config */
            $config = $app->make(ConfigRepository::class);
            $driver = $app->make($config->get(static::getConfigRootKeyName() . '.driver'));

            if ($driver instanceof DriverInterface) {
                return new AppVersionManager($driver->createRepository());
            }

            throw new \RuntimeException('Driver must implements [' . DriverInterface::class . '] interface');
        });
    }

    /**
     * Register Blade directives.
     *
     * @return void
     */
    protected function registerBlade(): void
    {
        $this->app->afterResolving('blade.compiler', static function (BladeCompiler $blade) {
            $blade->directive('app_version', static function (): string {
                return \sprintf('<?php echo resolve(\'%s\')->version(); ?>', ManagerInterface::class);
            });

            $blade->directive('app_build', static function (): string {
                return \sprintf('<?php echo resolve(\'%s\')->repository()->getBuild(); ?>', ManagerInterface::class);
            });

            $blade->directive('app_version_hash', static function ($length = null): string {
                return \sprintf('<?php echo resolve(\'%s\')->hashed(%d); ?>', ManagerInterface::class, empty($length)
                    ? 6 // default
                    : (int) $length);
            });
        });
    }

    /**
     * Register package helpers.
     *
     * @deprecated Will be remove since v4.x
     *
     * @return void
     */
    protected function registerHelpers(): void
    {
        require_once __DIR__ . '/../helpers/helpers.php';
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
            \realpath(static::getConfigPath()) => config_path(basename(static::getConfigPath())),
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
