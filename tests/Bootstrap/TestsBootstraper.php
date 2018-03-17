<?php

namespace AvtoDev\AppVersion\Tests\Bootstrap;

/**
 * Class TestsBootstraper.
 */
class TestsBootstraper extends AbstractTestsBootstraper
{
    /**
     * Returns path to the storage storage directory (for tests).
     *
     * @return string
     */
    public static function getStorageDirectoryPath()
    {
        return __DIR__ . '/../temp/storage';
    }

    /**
     * Make storage directory preparations.
     *
     * @return bool
     */
    protected function bootPrepareStorageDirectory()
    {
        $this->log('Prepare storage directory');

        if ($this->files->isDirectory($storage = static::getStorageDirectoryPath())) {
            if ($this->files->deleteDirectory($storage)) {
                $this->log('Previous storage directory deleted successfully');
            } else {
                $this->log(sprintf('Cannot delete directory "%s"', $storage));

                return false;
            }
        } else {
            $this->files->makeDirectory($storage, 0755, true);
        }

        $this->files->copyDirectory(__DIR__ . '/../../vendor/laravel/laravel/storage', $storage);

        return true;
    }
}
