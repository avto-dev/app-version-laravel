<?php

namespace AvtoDev\AppVersion\Tests;

use AvtoDev\AppVersion\AppVersionManager;

/**
 * Class AppVersionManagerTest.
 */
class AppVersionManagerTest extends AbstractTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        // Make clear
        foreach (glob(storage_path('app/APP*')) as $file_path) {
            unlink($file_path);
        }

        parent::tearDown();
    }

    /**
     * Test constructor.
     *
     * @return void
     */
    public function testConstructor()
    {
        $manager = new AppVersionManager([
            'major'               => $major = 1,
            'minor'               => $minor = '2',
            'patch'               => '3blabla4',
            'build'               => $build = '-_ foo bar',
            'format'              => '{{major}}.{{patch}}.{{minor}}_{{build}}',
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
    public function testValuesStoring()
    {
        $manager = new AppVersionManager($config = [
            'major'               => $major = 1,
            'minor'               => $minor = 0,
            'compiled_path'       => $compiled_path = storage_path('app/APP_VERSION'),
            'build_metadata_path' => $build_path = storage_path('app/APP_BUILD'),
        ]);
        $manager->setBuild("  alpha\n1\r.4 pre \t");
        $this->assertFileExists($build_path);

        $another_manager = new AppVersionManager($config);
        $this->assertEquals($build = 'alpha1.4 pre', $another_manager->build());
        $this->assertEquals("{$major}.{$minor}.0-{$build}", $formatted = $manager->formatted());
        $this->assertEquals("{$major}.{$minor}.0-{$build}", $manager->formatted()); // just for coverage
        $this->assertFileExists($compiled_path);

        $this->assertEquals($manager->version(), $formatted);
    }

    /**
     * Test files IO.
     *
     * @return void
     */
    public function testFilesIO()
    {
        $manager = new AppVersionManager($config = [
            'compiled_path'       => $compiled_path = storage_path('app/APP_VERSION_TEST'),
            'build_metadata_path' => $build_path = storage_path('app/APP_BUILD_TEST'),
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
    public function testHashed()
    {
        $manager = new AppVersionManager();

        $this->assertRegExp('~[a-z0-9]{5}~', $manager->hashed(5));

        $this->assertTrue(mb_strlen($manager->hashed()) === 6);
        $this->assertTrue(mb_strlen($manager->hashed(8)) === 8);
        $this->assertTrue(mb_strlen($manager->hashed(2)) === 2);
        $this->assertTrue(mb_strlen($manager->hashed(666)) === 40);
    }
}
