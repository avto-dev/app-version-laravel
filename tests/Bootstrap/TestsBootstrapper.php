<?php

namespace AvtoDev\AppVersion\Tests\Bootstrap;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Symfony\Component\Console\Output\ConsoleOutput;
use AvtoDev\AppVersion\Tests\Traits\CreatesApplicationTrait;

class TestsBootstrapper
{
    use CreatesApplicationTrait;

    /**
     * Prefix for 'magic' bootstrap methods.
     */
    const MAGIC_METHODS_PREFIX = 'boot';

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->app = $this->createApplication();

        $this->files = $this->app->make('files');

        // Перебираем все имена методов собственного класса
        foreach (get_class_methods(static::class) as $method_name) {
            // Если метод начинается с подстроки 'boot'
            if (Str::startsWith($method_name, static::MAGIC_METHODS_PREFIX)) {
                // То вызываем метод, передавая ему на вход массив коллекций (хотя передавать не обязательно)
                if (\call_user_func_array([$this, $method_name], []) !== true) {
                    throw new Exception(sprintf(
                        'Bootstrap method "%s" has non-true result. So, we cannot start tests for this reason',
                        $method_name
                    ));
                }
            }
        }

        $this->log(null);
    }

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
     * Show "styled" console message.
     *
     * @param string|null $message
     * @param string      $style
     */
    protected function log($message = null, $style = 'info')
    {
        /** @var ConsoleOutput|null $output */
        static $output = null;

        if (! ($output instanceof ConsoleOutput)) {
            $output = $this->app->make(ConsoleOutput::class);
        }

        $output->writeln($message === null
            ? ''
            : sprintf('<%1$s>> Bootstrap:</%1$s> <%2$s>%3$s</%2$s>', 'comment', $style, $message)
        );
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
