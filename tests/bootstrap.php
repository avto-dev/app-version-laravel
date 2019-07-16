<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

// Fix "ReflectionException: Class path.storage does not exist" error
require __DIR__ . '/../vendor/laravel/laravel/bootstrap/app.php';

$files = new Illuminate\Filesystem\Filesystem;

if ($files->isDirectory($storage = __DIR__ . '/temp/storage')) {
    $files->deleteDirectory($storage);
}

$files->copyDirectory(__DIR__ . '/../vendor/laravel/laravel/storage', $storage);
