<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Version File Path
    |--------------------------------------------------------------------------
    |
    | Path to the file which contains version value (file must be under VCS
    | control).
    |
    | Required for `Repository\FileRepository`.
    |
    */

    'repositories' => [
        'file'   => [
            'path' => base_path('VERSION'),
        ],
        'config' => [
            'major' => (int) env('APP_VERSION_MAJOR', 1),
            'minor' => (int) env('APP_VERSION_MINOR', 0),
            'patch' => (int) env('APP_VERSION_PATCH', 0),
            'build' => (string) env('APP_VERSION_BUILD', 0),

            'build_path' => storage_path('app/APP_BUILD'),
        ],
    ],

    'repository' => 'file',
];
