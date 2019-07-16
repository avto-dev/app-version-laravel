<?php

declare(strict_types = 1);

namespace AvtoDev\AppVersion\Tests;

use AvtoDev\AppVersion\AppVersionManager;

/**
 * @covers \AvtoDev\AppVersion\AppVersionManager<extended>
 */
class AppVersionManagerTest extends AbstractTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        // Make clear
        foreach (\glob($this->getStoragePath('app/APP*')) as $file_path) {
            \unlink($file_path);
        }

        parent::tearDown();
    }

    /**
     * Test constructor.
     *
     * @return void
     */
    public function testConstructor(): void
    {
        $manager = new AppVersionManager([
            'major'  => $major = 1,
            'minor'  => $minor = '2',
            'patch'  => '3blabla4',
            'build'  => $build = '-_ foo bar',
            'format' => '{{major}}.{{patch}}.{{minor}}_{{build}}',
        ]);

        $this->assertEquals(1, $manager->major());
        $this->assertEquals(2, $manager->minor());
        $this->assertEquals($patch = 34, $manager->patch());
        $this->assertEquals($build, $manager->build());
        $this->assertEquals("{$major}.{$patch}.{$minor}_{$build}", $manager->formatted());
    }

    /**
     * Test values storing.
     *
     * @return void
     */
    public function testValuesStoring(): void
    {
        $this->assertFileNotExists($build_path = $this->getStoragePath('app/APP_BUILD'));
        $manager = new AppVersionManager($config = [
            'major'               => $major = 1,
            'minor'               => $minor = 0,
            'compiled_path'       => $compiled_path = $this->getStoragePath('app/APP_VERSION'),
            'build_metadata_path' => $build_path,
        ]);
        $manager->setBuild("  alpha\n1\r.4 pre \t");
        $this->assertFileExists($build_path);

        $another_manager = new AppVersionManager($config);
        $this->assertEquals($build = 'alpha1.4 pre', $another_manager->build());
        $this->assertEquals("{$major}.{$minor}.0-{$build}", $formatted = $manager->formatted());
        $this->assertEquals("{$major}.{$minor}.0-{$build}", $manager->formatted()); // just for coverage
        $this->assertFileExists($compiled_path);

        $another_manager->refresh();

        $this->assertFileExists($compiled_path);

        $this->assertEquals($manager->version(), $formatted);
    }

    /**
     * Test files IO.
     *
     * @return void
     */
    public function testFilesIO(): void
    {
        $manager = new AppVersionManager($config = [
            'compiled_path'       => $compiled_path = $this->getStoragePath('app/APP_VERSION_TEST'),
            'build_metadata_path' => $build_path = $this->getStoragePath('app/APP_BUILD_TEST'),
        ]);

        $this->assertFileNotExists($compiled_path);
        $this->assertFileNotExists($build_path);
        $manager->setBuild($build = 'beta2');

        $manager->refresh();

        $this->assertFileExists($compiled_path);
        $this->assertFileExists($build_path);
        $this->assertEquals($build, file_get_contents($build_path));
    }

    /**
     * Test hashed method.
     *
     * @return void
     */
    public function testHashed(): void
    {
        $manager = new AppVersionManager;

        $this->assertRegExp('~[a-z0-9]{5}~', $manager->hashed(5));

        $this->assertSame(6, mb_strlen($manager->hashed()));
        $this->assertSame(8, mb_strlen($manager->hashed(8)));
        $this->assertSame(2, mb_strlen($manager->hashed(2)));
        $this->assertSame(40, mb_strlen($manager->hashed(666)));
    }

    /**
     * Test version files not created on setting same value.
     *
     * @return void
     */
    public function testVersionFilesNotCreatedOnSettingSameValue(): void
    {
        $manager = new AppVersionManager([
            'build'               => $build = 'alpha-1',
            'compiled_path'       => $compiled_path = $this->getStoragePath('app/APP_VERSION_' . \random_int(0, 999)),
            'build_metadata_path' => $build_path = $this->getStoragePath('app/APP_BUILD_' . \random_int(0, 999)),
        ]);

        $this->assertFileNotExists($compiled_path);
        $this->assertFileNotExists($build_path);

        // Set self (already setted) build value
        $manager->setBuild($build);

        // And after that - files must be NOT created
        $this->assertFileNotExists($compiled_path);
        $this->assertFileNotExists($build_path);
    }

    /**
     * Test build file created on setting different build value.
     *
     * @return void
     */
    public function testBuildFilesCreatedOnSettingDifferentBuildValue(): void
    {
        $manager = new AppVersionManager([
            'build'               => $build = 'alpha-2',
            'compiled_path'       => $compiled_path = $this->getStoragePath('app/APP_VERSION_' . \random_int(0, 999)),
            'build_metadata_path' => $build_path = $this->getStoragePath('app/APP_BUILD_' . \random_int(0, 999)),
        ]);

        $this->assertFileNotExists($compiled_path);
        $this->assertFileNotExists($build_path);

        // Set self new (different) build value
        $manager->setBuild($new_build = $build . \random_int(1, 99));

        $this->assertEquals($new_build, $manager->build());

        // And after that - files must be created
        $this->assertStringEqualsFile($build_path, $manager->build());
        $this->assertStringEqualsFile($compiled_path, $manager->formatted());
    }

    /**
     * Test creating versions files on refresh.
     *
     * @return void
     */
    public function testCreatingVersionsFilesOnRefresh(): void
    {
        $manager = new AppVersionManager([
            'major'               => 1,
            'minor'               => 2,
            'patch'               => 3,
            'format'              => '{{major}}.{{minor}}.{{patch}}_{{build}}',
            'build'               => $build = 'alpha-3',
            'compiled_path'       => $compiled_path = $this->getStoragePath('app/APP_VERSION_' . \random_int(0, 999)),
            'build_metadata_path' => $build_path = $this->getStoragePath('app/APP_BUILD_' . \random_int(0, 999)),
        ]);

        $this->assertFileNotExists($compiled_path);
        $this->assertFileNotExists($build_path);

        $manager->refresh();

        $this->assertStringEqualsFile($compiled_path, $manager->formatted());
        // Build file must be not created because build version is not changed
        $this->assertFileNotExists($build_path);

        // But after that - build file must be exists
        $manager->setBuild($new_build = 'beta3');

        $this->assertEquals($new_build, $manager->build());
        $this->assertStringEqualsFile($build_path, $manager->build());
        $this->assertEquals($manager->formatted(), $new_formatted = "1.2.3_{$new_build}");
        // And version file - too
        $this->assertStringEqualsFile($compiled_path, $new_formatted);
    }

    public function testBuildValueCanBeChangedByConfiguration(): void
    {
        $manager = new AppVersionManager([
            'build'               => $build = 'alpha-1',
            'compiled_path'       => $compiled_path = $this->getStoragePath('app/APP_VERSION_' . \random_int(0, 999)),
            'build_metadata_path' => $build_path = $this->getStoragePath('app/APP_BUILD_' . \random_int(0, 999)),
        ]);

        $this->assertEquals($build, $manager->build());
        $this->assertFileNotExists($build_path);

        $manager->refresh();

        $manager = new AppVersionManager([
            'build'               => $build2 = 'alpha-2',
            'compiled_path'       => $compiled_path,
            'build_metadata_path' => $build_path,
        ]);

        $this->assertEquals($build2, $manager->build());
        $this->assertFileNotExists($build_path);

        $manager->setBuild($new_build = 'beta-1');
        $this->assertFileExists($build_path);
        $this->assertEquals($new_build, $manager->build());
        $this->assertStringEqualsFile($build_path, $manager->build());
    }

    /**
     * Get path for storage files.
     *
     * @param string $additional_path
     *
     * @return string
     */
    protected function getStoragePath($additional_path = ''): string
    {
        return storage_path($additional_path);
    }
}
