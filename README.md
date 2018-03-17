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

{% Более полное описание пакета, которое позволяет принять решение о его предназначении и применимости в том проекте, работа над которым привела пользователя в данный репозиторий. %}

## Установка

Для установки данного пакета выполните в терминале следующую команду:

```shell
$ composer require avto-dev/app-version-laravel "^1.0"
```

> Для этого необходим установленный `composer`. Для его установки перейдите по [данной ссылке][getcomposer].

> Обратите внимание на то, что необходимо фиксировать мажорную версию устанавливаемого пакета.

Если вы используете Laravel версии 5.5 и выше, то сервис-провайдер данного пакета будет зарегистрирован автоматически. В противном случае вам необходимо самостоятельно зарегистрировать сервис-провайдер в секции `providers` файла `./config/app.php`:

```php
'providers' => [
    // ...
    AvtoDev\AppVersion\AppVersionServiceProvider::class,
]
```

## Использование

{% В данном блоке следует максимально подробно рассказать о том, какие задачи решает данный пакет, какое API предоставляет разработчику, из каких компонентов состоит и привести примеры использования с примерами кода. Привести максимально подробне разъяснения и комментарии. %}

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
