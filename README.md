
<p align="center">
  <img src="https://laravel.com/assets/img/components/logo-laravel.svg" alt="Laravel" width="240" />
</p>

# AppVersion for Laravel applications

[![Version][badge_version]][link_packagist]
[![Build Status][badge_build_status]][link_build_status]
[![StyleCI][badge_styleci]][link_styleci]
[![Coverage][badge_coverage]][link_coverage]
[![Code Quality][badge_quality]][link_coverage]
[![Issues][badge_issues]][link_issues]
[![License][badge_license]][link_license]
[![Downloads count][badge_downloads_count]][link_packagist]

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
>     AvtoDev\AppVersion\AppVersionServiceProvider::class,
> ]
> ```

После этого "опубликуйте" конфигурационный файл:

```shell
$ php artisan vendor:publish --provider="AvtoDev\\AppVersion\\AppVersionServiceProvider"
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

Для использования менеджера версии приложения в DI - просто запросите реализацию по интерфейсу `AvtoDev\AppVersion\Contracts\AppVersionManagerContract`. Пример использования:

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
```

### Хэлперы

Так же вам доступны следующие хэлперы:

```php
<?php

app_version(); // 1.0.0-alpha2
app_build(); // alpha2
```

### Тестирование

Для тестирования данного пакета используется фреймворк `phpunit`. Для запуска тестов выполните в терминале:

```shell
$ git clone git@github.com:avto-dev/app-version-laravel.git ./app-version-laravel && cd $_
$ composer install
$ composer test
```

## Поддержка и развитие

Если у вас возникли какие-либо проблемы по работе с данным пакетом, пожалуйста, создайте соответствующий `issue` в данном репозитории.

Если вы способны самостоятельно реализовать тот функционал, что вам необходим - создайте PR с соответствующими изменениями. Крайне желательно сопровождать PR соответствующими тестами, фиксирующими работу ваших изменений. После проверки и принятия изменений будет опубликована новая минорная версия.

## Лицензирование

Код данного пакета распространяется под лицензией [MIT][link_license].

[badge_version]:https://img.shields.io/packagist/v/avto-dev/app-version-laravel.svg?style=flat&maxAge=30
[badge_downloads_count]:https://img.shields.io/packagist/dt/avto-dev/app-version-laravel.svg?style=flat&maxAge=30
[badge_license]:https://img.shields.io/packagist/l/avto-dev/app-version-laravel.svg?style=flat&maxAge=30
[badge_build_status]:https://scrutinizer-ci.com/g/avto-dev/app-version-laravel/badges/build.png?b=master
[badge_styleci]:https://styleci.io/repos/125632078/shield
[badge_coverage]:https://scrutinizer-ci.com/g/avto-dev/app-version-laravel/badges/coverage.png?b=master
[badge_quality]:https://scrutinizer-ci.com/g/avto-dev/app-version-laravel/badges/quality-score.png?b=master
[badge_issues]:https://img.shields.io/github/issues/avto-dev/app-version-laravel.svg?style=flat&maxAge=30
[link_packagist]:https://packagist.org/packages/avto-dev/app-version-laravel
[link_styleci]:https://styleci.io/repos/125632078/
[link_license]:https://github.com/avto-dev/app-version-laravel/blob/master/LICENSE
[link_build_status]:https://scrutinizer-ci.com/g/avto-dev/app-version-laravel/build-status/master
[link_coverage]:https://scrutinizer-ci.com/g/avto-dev/app-version-laravel/?branch=master
[link_issues]:https://github.com/avto-dev/app-version-laravel/issues
[getcomposer]:https://getcomposer.org/download/
