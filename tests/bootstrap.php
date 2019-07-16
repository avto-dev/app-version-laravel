<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

$files = new Illuminate\Filesystem\Filesystem;

if ($files->isDirectory($storage = __DIR__ . '/temp/storage')) {
    $files->deleteDirectory($storage);
}

$files->copyDirectory(__DIR__ . '/../vendor/laravel/laravel/storage', $storage);
