<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Repository Driver Class
    |--------------------------------------------------------------------------
    |
    | Driver creates repository instance for accessing to the version data.
    |
    | Feel free to create your own driver!
    |
    */

    'driver' => AvtoDev\AppVersion\Drivers\FileDriver::class,

    /*
    |--------------------------------------------------------------------------
    | File Repository Settings
    |--------------------------------------------------------------------------
    |
    | Here you may specify settings, which specific for `Drivers\FileDriver`
    | (it uses `Repository\FileRepository`).
    |
    | File driver store full version value in one text file, and follows
    | semantic versioning specification. Any version values can be changed in
    | runtime.
    |
    | Allowed keys:
    |   - `path` (path to the file with version value)
    |
    */

    'file' => [
        'path' => base_path('VERSION'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Config File Repository Settings
    |--------------------------------------------------------------------------
    |
    | This package allows to use application config file as version value
    | storage. This feature requires `Drivers\ConfigFileDriver` usage (last
    | uses `Repository\ConfigFileRepository`).
    |
    | Only build version value can be changed in runtime.
    |
    | Allowed keys:
    |   - `major` (major version value)
    |   - `minor` (minor version value)
    |   - `patch` (patch version value)
    |   - `build` (build version value)
    |   - `build_file` (path to the file with build version value)
    |
    */

    'config' => [
        'major' => (int) env('APP_VERSION_MAJOR', 1),
        'minor' => (int) env('APP_VERSION_MINOR', 0),
        'patch' => (int) env('APP_VERSION_PATCH', 0),
        'build' => (string) env('APP_VERSION_BUILD', 0),

        'build_file' => storage_path('app/APP_BUILD'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Changelog File Repository Settings
    |--------------------------------------------------------------------------
    |
    | Version reading can be implemented using `CHANGELOG.md` file (that
    | follows `https://keepachangelog.com/` recommendations). This feature
    | requires `Repository\ChangelogFileRepository`, and reads latest
    | valid version from header.
    |
    | Version value setters cannot be used in this case.
    |
    | Allowed keys:
    |   - `path` (path to the `CHANGELOG.md` file)
    |
    */

    'changelog' => [
        'path' => base_path('CHANGELOG.md'),
    ],
];
