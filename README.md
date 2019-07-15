
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

> Скриншот позаимствован из репозитория `antonioribeiro/version`

По умолчанию Laravel не имеет встроенного механизма для работы со значением версии приложения, и данный пакет предназначен восполнить данный недостаток. Используя его вы сможете в произвольном месте вашего кода получать значение версии приложения, хранить его в отдельном файле (для реализации возможности его чтения, например - другими приложениями), и обновлять версию сборки при необходимости прямо во время работы приложения, или консоли.

## Установка

Для установки данного пакета выполните в терминале следующую команду:

```shell
$ composer require avto-dev/app-version-laravel "^1.0"
```

> Для этого необходим установленный `composer`. Для его установки перейдите по [данной ссылке][getcomposer].

> Обратите внимание на то, что необходимо фиксировать мажорную версию устанавливаемого пакета.

> Если вы используете Laravel версии 5.5 и выше, то сервис-провайдер данного пакета будет зарегистрирован автоматически. В противном случае вам необходимо самостоятельно зарегистрировать сервис-провайдер в секции `providers` файла `./config/app.php`:
> 
> ```php
> 'providers' => [
>     // ...
>     AvtoDev\AppVersion\ServiceProvider::class,
> ]
> ```

После этого "опубликуйте" конфигурационный файл:

```shell
$ php artisan vendor:publish --provider="AvtoDev\\AppVersion\\ServiceProvider"
```

И произведите необходимые настройки в файле `./config/version.php`. Каждое значение в конфигурационном файле имеет подробное описание.

Для того, чтоб файл со значением версии обновлялся автоматически, добавьте следующую строку в секцию `post-autoload-dump` файла `composer.json` вашего приложения:

```json
"post-autoload-dump": [
    "@php artisan version --refresh"
]
```

## Использование

В целях оптимизации и возможности доступа ко значению версии другими приложениями данные версии и сборки (билда) приложения по умолчанию хранятся в файлах `./storage/app/APP_VERSION` и `./storage/app/APP_BUILD` соответственно (находятся **не** под гитом).

> Помните, что при ручном обновлении конфигурационного файла с версией вам будет выполнить команду `php artisan version --refresh`.

Пути к файлам вы, разумеется, можете переопределить на произвольные. Так же вы можете установить произвольный формат вывода версии (`1.0.0-beta` или `ver. 1.0.0 (build beta)`) - как только пожелаете.

Для использования менеджера версии приложения в DI - просто запросите реализацию по интерфейсу `AvtoDev\AppVersion\Contracts\AppVersionManagerContract`. Реализованные методы также можете посмотреть в данном интерфейсе. Пример использования в DI:

```php
<?php

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
    public function handle(AppVersionManagerContract $manager)
    {
        $manager->formatted(); // 1.0.0-alpha2
    }
}
```

### Artisan-команды

При использовании данного пакета вам доступны следующие artisan-команды и их аргументы:

Сигнатура команды | Описание
----------------- | --------
`version` | Выводит значение версии приложения
`version --build` | Выводит только значение сборки приложения
`version --set-build=alpha2` | Устанавливает значение сборки приложения равное `alpha2`
`version --refresh` | Обновляет (пересоздаёт) файлы со значениями версий

### Blade

В ваших blade-шаблонах вы можете использовать следующие конструкции:

```smarty
Application version: @app_version
Build version: @app_build
Application version hash: @app_version_hash
```

### Хэлперы

Так же вам доступны следующие хэлперы:

```php
<?php

app_version(); // 1.0.0-alpha2
app_build(); // alpha2
app_version_hash(); // 965c6f
```

### Testing

For package testing we use `phpunit` framework. Just write into your terminal:

```shell
$ git clone git@github.com:avto-dev/app-version-laravel.git ./app-version-laravel && cd $_
$ composer install
$ composer test
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
[smspilot_home]:https://smspilot.ru/
[smspilot_get_api_key]:https://smspilot.ru/my-settings.php#api
[smspilot_sender_names]:https://smspilot.ru/my-sender.php
[laravel_notifications]:https://laravel.com/docs/5.5/notifications
[getcomposer]:https://getcomposer.org/download/
