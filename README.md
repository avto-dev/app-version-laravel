<p align="center">
  <img src="https://laravel.com/assets/img/components/logo-laravel.svg" alt="Laravel" width="240" />
</p>

# AppVersion for Laravel applications

[![Version][badge_packagist_version]][link_packagist]
[![Version][badge_php_version]][link_packagist]
[![Build Status][badge_build_status]][link_build_status]
[![Coverage][badge_coverage]][link_coverage]
[![Downloads count][badge_downloads_count]][link_packagist]
[![License][badge_license]][link_license]

![screenshot](https://hsto.org/webt/1k/1o/hb/1k1ohba9ap2oy5e0kq4t0rulpls.png)

> Picture taken from `antonioribeiro/version` repository

Laravel does not have included mechanism for a working with application version, and this package can fix this flaw.

## Install

Require this package with composer using the following command:

```shell
$ composer require avto-dev/app-version-laravel "^2.0"
```

> Installed `composer` is required ([how to install composer][getcomposer]).

> You need to fix the major version of package.

> If you wants to disable package service-provider auto discover, just add into your `composer.json` next lines:
>
> ```json
> {
>     "extra": {
>         "laravel": {
>             "dont-discover": [
>                 "avto-dev/app-version-laravel"
>             ]
>         }
>     }
> }
> ```

After that you should "publish" configuration file (`./config/version.php`) using next command:

```bash
$ php artisan vendor:publish --provider="AvtoDev\\AppVersion\\ServiceProvider"
```

And don't forget to add next line into your `composer.json` file:

```json
{
    "scripts": {
        "post-autoload-dump": [
            "@php artisan version --refresh"
        ]
    }
}
```

## Usage

For application performance and sharing access to the version values - they stored not only in configuration file, but in `./storage/app/APP_VERSION` and `./storage/app/APP_BUILD` also (**not** under git, of course).

> You should remember to execute `php artisan version --refresh` command after manual version changing in configuration file.

Of course, you can set own paths to these files and use your own format (`1.0.0-beta` or `ver. 1.0.0 (build beta)`, for example).

If you wanna get access to the version manager using DI - just request `AvtoDev\AppVersion\Contracts\AppVersionManagerContract`:

```php
<?php declare(strict_types = 1);

namespace App\Console\Commands;

use AvtoDev\AppVersion\Contracts\AppVersionManagerContract;

class SomeCommand extends \Illuminate\Console\Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'some:command';
    
    /**
     * Execute the console command.
     *
     * @param AppVersionManagerContract $manager
     *
     * @return void
     */
    public function handle(AppVersionManagerContract $manager): void
    {
        $manager->formatted(); // 1.0.0-alpha2
    }
}
```

### Artisan commands

Command signature            | Description
---------------------------- | -----------------------------
`version`                    | Shows application version
`version --build`            | Shown only build value
`version --set-build=alpha2` | Set build value `alpha2`
`version --refresh`          | Refresh build values in files

### Blade

Blade compiler allows next directives:

```smarty
Application version: @app_version
Build version: @app_build
Application version hash: @app_version_hash
```

### Helpers

Also you can use next helpers:

```php
<?php

app_version(); // 1.0.0-alpha2
app_build(); // alpha2
app_version_hash(); // 965c6f
```

### Testing

For package testing we use `phpunit` framework and `docker-ce` + `docker-compose` as develop environment. So, just write into your terminal after repository cloning:

```bash
$ make build
$ make latest # or 'make lowest'
$ make test
```

## Changes log

[![Release date][badge_release_date]][link_releases]
[![Commits since latest release][badge_commits_since_release]][link_commits]

Changes log can be [found here][link_changes_log].

## Support

[![Issues][badge_issues]][link_issues]
[![Issues][badge_pulls]][link_pulls]

If you will find any package errors, please, [make an issue][link_create_issue] in current repository.

## License

This is open-sourced software licensed under the [MIT License][link_license].

[badge_packagist_version]:https://img.shields.io/packagist/v/avto-dev/app-version-laravel.svg?maxAge=180
[badge_php_version]:https://img.shields.io/packagist/php-v/avto-dev/app-version-laravel.svg?longCache=true
[badge_build_status]:https://travis-ci.org/avto-dev/app-version-laravel.svg?branch=master
[badge_coverage]:https://img.shields.io/codecov/c/github/avto-dev/app-version-laravel/master.svg?maxAge=60
[badge_downloads_count]:https://img.shields.io/packagist/dt/avto-dev/app-version-laravel.svg?maxAge=180
[badge_license]:https://img.shields.io/packagist/l/avto-dev/app-version-laravel.svg?longCache=true
[badge_release_date]:https://img.shields.io/github/release-date/avto-dev/app-version-laravel.svg?style=flat-square&maxAge=180
[badge_commits_since_release]:https://img.shields.io/github/commits-since/avto-dev/app-version-laravel/latest.svg?style=flat-square&maxAge=180
[badge_issues]:https://img.shields.io/github/issues/avto-dev/app-version-laravel.svg?style=flat-square&maxAge=180
[badge_pulls]:https://img.shields.io/github/issues-pr/avto-dev/app-version-laravel.svg?style=flat-square&maxAge=180
[link_releases]:https://github.com/avto-dev/app-version-laravel/releases
[link_packagist]:https://packagist.org/packages/avto-dev/app-version-laravel
[link_build_status]:https://travis-ci.org/avto-dev/app-version-laravel
[link_coverage]:https://codecov.io/gh/avto-dev/app-version-laravel/
[link_changes_log]:https://github.com/avto-dev/app-version-laravel/blob/master/CHANGELOG.md
[link_issues]:https://github.com/avto-dev/app-version-laravel/issues
[link_create_issue]:https://github.com/avto-dev/app-version-laravel/issues/new/choose
[link_commits]:https://github.com/avto-dev/app-version-laravel/commits
[link_pulls]:https://github.com/avto-dev/app-version-laravel/pulls
[link_license]:https://github.com/avto-dev/app-version-laravel/blob/master/LICENSE
[getcomposer]:https://getcomposer.org/download/
