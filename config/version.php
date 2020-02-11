<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Version
    |--------------------------------------------------------------------------
    |
    | Here you may specify version of current application.
    |
    | @see <https://semver.org/> Semantic Versioning
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Major Version
    |--------------------------------------------------------------------------
    |
    | Increment when you make incompatible with previous versions changes.
    |
    */
    'major' => (int) env('APP_VERSION_MAJOR', 1),

    /*
    |--------------------------------------------------------------------------
    | Minor Version
    |--------------------------------------------------------------------------
    |
    | Increment when you add functionality in a backwards-compatible manner.
    |
    */
    'minor' => (int) env('APP_VERSION_MINOR', 0),

    /*
    |--------------------------------------------------------------------------
    | Patch Version
    |--------------------------------------------------------------------------
    |
    | Increment when you make backwards-compatible bug fixes.
    |
    */
    'patch' => (int) env('APP_VERSION_PATCH', 0),

    /*
    |--------------------------------------------------------------------------
    | Build Metadata
    |--------------------------------------------------------------------------
    |
    | Increment (change) when you make backwards-compatible bug fixes.
    |
    */
    'build' => (string) env('APP_VERSION_BUILD', 0),

    /*
    |--------------------------------------------------------------------------
    | Output Format
    |--------------------------------------------------------------------------
    |
    | Here you can set output (as string) version format.
    |
    */
    'format' => (string) env('APP_VERSION_FORMAT', '{{major}}.{{minor}}.{{patch}}-{{build}}'),

    /*
    |--------------------------------------------------------------------------
    | Compiled Version File Path
    |--------------------------------------------------------------------------
    |
    | We will store compiled version value in a single file for speed-up.
    |
    | Also you can read it in any another applications.
    |
    */
    'compiled_path' => (string) env('APP_VERSION_COMPILED_PATH', storage_path('app/APP_VERSION')),

    /*
    |--------------------------------------------------------------------------
    | Build Metadata File Path
    |--------------------------------------------------------------------------
    |
    | Build metadata will be stored into own file. So, we can change it in
    | runtime.
    |
    | Also you can read it in any another applications.
    |
    */
    'build_metadata_path' => (string) env('APP_VERSION_BUILD_METADATA_PATH', storage_path('app/APP_BUILD')),

];
