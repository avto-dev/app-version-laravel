<p align="center">
  <img src="https://laravel.com/assets/img/components/logo-laravel.svg" alt="Laravel" width="240" />
</p>

# AppVersion for Laravel applications

[![Version][badge_packagist_version]][link_packagist]
[![PHP Version][badge_php_version]][link_packagist]
[![Build Status][badge_build_status]][link_build_status]
[![Coverage][badge_coverage]][link_coverage]
[![Downloads count][badge_downloads_count]][link_packagist]
[![License][badge_license]][link_license]

![screenshot](https://habrastorage.org/webt/eo/xv/6r/eoxv6rk__16eb7vminjsrh15nqo.png)

> Picture taken from `antonioribeiro/version` repository

Laravel does not have included mechanism for a working with application version, and this package can fix this flaw.

## Install

Require this package with composer using the following command:

```shell
$ composer require avto-dev/app-version-laravel "^3.0"
```

> Installed `composer` is required ([how to install composer][getcomposer]).

> You need to fix the major version of package.

After that you should "publish" configuration file (`./config/version.php`) using next command:

```bash
$ php artisan vendor:publish --provider="AvtoDev\\AppVersion\\ServiceProvider"
```

## Usage

This package provides application version manager (`AppVersionManager`) and:

- Version value repositories _(abstraction layer above version data)_
- Repository drivers _(also known as "factories" - they creates configured repository instance)_

> You can write your own implementations, and use them (only correct configuration is required).

Built-in "storage" types:

- Plain file with version definition;
- Application configuration file (`version.config` by default);
- `CHANGELOG.md` file (extracts last defined version value).

If you wanna get access to the version manager using DI - just request `AvtoDev\AppVersion\AppVersionManagerInterface`:

```php
<?php

namespace App\Console\Commands;

use AvtoDev\AppVersion\AppVersionManagerInterface;

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
     * @param AppVersionManagerInterface $manager
     *
     * @return void
     */
    public function handle(AppVersionManagerInterface $manager): void
    {
        $manager->version(); // e.g.: 1.0.0-alpha2
    }
}
```

### Artisan commands

| Command signature                               | Description                                       |
|-------------------------------------------------|---------------------------------------------------|
| `version`                                       | Shows application version                         |
| `version --get-segment=major/minor/patch/build` | Shown only major/minor/patch/build version value  |
| `version --set-build=alpha2`                    | Set build value `alpha2`                          |
| `version --set-version=1.2.3-alpha`             | Complex version setter                            |

### Blade

Blade compiler allows next directives:

```smarty
Application version: @app_version
Build version: @app_build
Application version hash: @app_version_hash
```

### Testing

For package testing we use `phpunit` framework and `docker` with `compose` plugin as develop environment. So, just write into your terminal after repository cloning:

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
[badge_build_status]:https://img.shields.io/github/actions/workflow/status/avto-dev/app-version-laravel/tests.yml
[badge_coverage]:https://img.shields.io/codecov/c/github/avto-dev/app-version-laravel/master.svg?maxAge=60
[badge_downloads_count]:https://img.shields.io/packagist/dt/avto-dev/app-version-laravel.svg?maxAge=180
[badge_license]:https://img.shields.io/packagist/l/avto-dev/app-version-laravel.svg?longCache=true
[badge_release_date]:https://img.shields.io/github/release-date/avto-dev/app-version-laravel.svg?style=flat-square&maxAge=180
[badge_commits_since_release]:https://img.shields.io/github/commits-since/avto-dev/app-version-laravel/latest.svg?style=flat-square&maxAge=180
[badge_issues]:https://img.shields.io/github/issues/avto-dev/app-version-laravel.svg?style=flat-square&maxAge=180
[badge_pulls]:https://img.shields.io/github/issues-pr/avto-dev/app-version-laravel.svg?style=flat-square&maxAge=180
[link_releases]:https://github.com/avto-dev/app-version-laravel/releases
[link_packagist]:https://packagist.org/packages/avto-dev/app-version-laravel
[link_build_status]:https://github.com/avto-dev/app-version-laravel/actions
[link_coverage]:https://codecov.io/gh/avto-dev/app-version-laravel/
[link_changes_log]:https://github.com/avto-dev/app-version-laravel/blob/master/CHANGELOG.md
[link_issues]:https://github.com/avto-dev/app-version-laravel/issues
[link_create_issue]:https://github.com/avto-dev/app-version-laravel/issues/new/choose
[link_commits]:https://github.com/avto-dev/app-version-laravel/commits
[link_pulls]:https://github.com/avto-dev/app-version-laravel/pulls
[link_license]:https://github.com/avto-dev/app-version-laravel/blob/master/LICENSE
[getcomposer]:https://getcomposer.org/download/
