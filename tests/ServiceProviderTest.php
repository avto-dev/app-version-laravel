<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Tests;

use App\Console\Kernel;
use AvtoDev\AppVersion\ServiceProvider;
use AvtoDev\AppVersion\AppVersionManager;
use Illuminate\View\Compilers\BladeCompiler;
use AvtoDev\AppVersion\Commands\VersionCommand;
use AvtoDev\AppVersion\Drivers\DriverInterface;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use AvtoDev\AppVersion\AppVersionManagerInterface as ManagerInterface;

/**
 * @coversDefaultClass \AvtoDev\AppVersion\ServiceProvider
 *
 * @group  provider
 */
class ServiceProviderTest extends AbstractTestCase
{
    /**
     * @covers ::getConfigRootKeyName
     *
     * @return void
     */
    public function testGetConfigRootKeyName(): void
    {
        $this->assertSame('version', ServiceProvider::getConfigRootKeyName());
    }

    /**
     * @covers ::getConfigPath
     *
     * @return void
     */
    public function testGetConfigPath(): void
    {
        $this->assertSame(
            \realpath(__DIR__ . '/../config/version.php'),
            \realpath(ServiceProvider::getConfigPath())
        );

        $this->assertFileExists(ServiceProvider::getConfigPath());
    }

    /**
     * @covers ::initializeConfigs
     * @covers ::register
     *
     * @return void
     */
    public function testRegisterConfigs(): void
    {
        $package_config_src    = \realpath(ServiceProvider::getConfigPath());
        $package_config_target = $this->app->configPath(\basename(ServiceProvider::getConfigPath()));

        $this->assertSame(
            $package_config_target,
            ServiceProvider::$publishes[ServiceProvider::class][$package_config_src]
        );

        $this->assertSame(
            $package_config_target,
            ServiceProvider::$publishGroups['config'][$package_config_src],
            "Publishing group value {$package_config_target} was not found"
        );
    }

    /**
     * @covers ::registerArtisanCommands
     * @covers ::register
     *
     * @return void
     */
    public function testArtisanCommandRegistration(): void
    {
        $command_classes = \array_map('\\get_class', $this->app->make(Kernel::class)->all());

        $this->assertContains(VersionCommand::class, $command_classes);
    }

    /**
     * @covers ::registerHelpers
     * @covers ::register
     *
     * @return void
     */
    public function testHelpersRegistration(): void
    {
        $this->assertTrue(\function_exists('app_' . 'version'));
        $this->assertTrue(\function_exists('app_' . 'build'));
        $this->assertTrue(\function_exists('app_' . 'version_hash'));

        $manager = $this->overrideVersionManager();
        $this->setRandomVersion($manager);

        $this->assertSame($version = $manager->version(), app_version());
        $this->assertSame($version, \app_version());

        $this->assertSame($build = $manager->repository()->getBuild(), app_build());
        $this->assertSame($build, \app_build());

        $this->assertSame($hashed = $manager->hashed(), app_version_hash());
        $this->assertSame($hashed, \app_version_hash());
        $this->assertSame($manager->hashed(12), app_version_hash(12));
    }

    /**
     * @covers ::registerManager
     * @covers ::register
     *
     * @return void
     */
    public function testManagerRegistration(): void
    {
        /** @var ConfigRepository $config */
        $config = $this->app->make(ConfigRepository::class);
        /** @var ManagerInterface $manager */
        $manager = $this->app->make(ManagerInterface::class);
        /** @var DriverInterface $driver */
        $driver = $this->app->make($config->get('version.driver'));

        $this->assertInstanceOf(AppVersionManager::class, $manager);
        $this->assertInstanceOf(\get_class($driver->createRepository()), $manager->repository());
    }

    /**
     * @covers ::registerBlade
     * @covers ::register
     *
     * @return void
     */
    public function testBladeDirectivesRendering(): void
    {
        /** @var ViewFactory $view */
        $view = $this->app->make(ViewFactory::class);
        /** @var BladeCompiler $compiler */
        $compiler = $this->app->make(BladeCompiler::class);
        /** @var array<string, \Closure> $custom_directives */
        $custom_directives = $compiler->getCustomDirectives();

        $manager = $this->overrideVersionManager();
        $this->setRandomVersion($manager);

        $view->addNamespace('stubs', __DIR__ . '/stubs/view');

        $this->assertArrayHasKey('app_version', $custom_directives);
        $this->assertIsString($custom_directives['app_version']());
        $this->assertSame(
            "Application version: {$manager->formatted()}",
            $view->make('stubs::app_version', [], [])->render()
        );

        $this->assertArrayHasKey('app_build', $custom_directives);
        $this->assertIsString($custom_directives['app_build']());
        $this->assertSame(
            "Build version: {$manager->repository()->getBuild()}",
            $view->make('stubs::app_build', [], [])->render()
        );

        $this->assertArrayHasKey('app_version_hash', $custom_directives);
        $this->assertIsString($custom_directives['app_version_hash']());
        $this->assertSame(
            "Application version hash: {$manager->hashed()}, 16 = {$manager->hashed(16)}",
            $view->make('stubs::app_version_hash', [], [])->render()
        );
    }
}
