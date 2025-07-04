# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog][keepachangelog] and this project adheres to [Semantic Versioning][semver].

## v3.8.0

### Added

- Laravel `12.x` support
- Using `docker` with `compose` plugin instead of `docker-compose` for test environment

### Changed

- Minimal require PHP version now is `8.2`
- Version of `composer` in docker container updated up to `2.8.9`

## v3.7.0

### Added

- Laravel `11.x` support

### Changed

- Minimal require PHP version now is `8.1`
- Minimal Laravel version now is `10.0`
- Version of `composer` in docker container updated up to `2.7.4`
- Updated dev dependencies

## v3.6.0

### Added

- Laravel `10.x` support

## v3.5.0

### Changed

- Minimal require PHP version now is `8.0`
- Minimal Laravel version now is `9.0`
- Version of `composer` in docker container updated up to `2.5.3`

## v3.4.0

### Added

- Laravel `9.x` is supported now

## v3.3.0

### Added

- Support PHP `8.x`

### Changed

- Minimal PHP version now is `7.3`
- Composer `2.x` is supported now

## v3.2.0

### Changed

- Laravel `8.x` is supported now
- Minimal Laravel version now is `6.0` (Laravel `5.5` LTS got last security update August 30th, 2020)
- CI completely moved from "Travis CI" to "Github Actions" _(travis builds disabled)_
- Minimal required PHP version now is `7.2`

## v3.1.0

### Changed

- Do not throws an exception when file does not exist in `FileRepository` (uses fallback version `0.0.0` instead)
- Maximal `illuminate/*` packages version now is `7.*`

## v3.0.0

### Added

- PHP `7.4` supports ang tests running
- Method `->repository(): RepositoryInterface` in `AvtoDev\AppVersion\AppVersionManager`
- Interfaces:
  - `AvtoDev\AppVersion\Drivers\DriverInterface`
  - `AvtoDev\AppVersion\Repositories\RepositoryInterface`
- Artisan command `version` supports new arguments - `--get-segment`, `--set-version`
- Drivers _(repository factories)_:
  - `AvtoDev\AppVersion\Drivers\ChangelogFileDriver`
  - `AvtoDev\AppVersion\Drivers\ConfigFileDriver`
  - `AvtoDev\AppVersion\Drivers\FileDriver`
- Repositories:
  - `AvtoDev\AppVersion\Repositories\ChangelogFileRepository`
  - `AvtoDev\AppVersion\Repositories\ConfigFileRepository`
  - `AvtoDev\AppVersion\Repositories\FileRepository`
  - `AvtoDev\AppVersion\Repositories\NullRepository`

### Changed

- Config file location (`./src/config/version.php` &rarr; `./config/version.php`)
- Config file structure (totally)
- Minimal `symfony/console` version now is `^4.4` _(reason: <https://github.com/symfony/symfony/issues/32750>)_
- `->formatted()` method signature to `->formatted(string $format)` in `AvtoDev\AppVersion\AppVersionManager`
- Interface `AvtoDev\AppVersion\Contracts\AppVersionManagerContract` &rarr; `AvtoDev\AppVersion\AppVersionManagerInterface`

### Removed

- Facade `AvtoDev\AppVersion\AppVersionFacade`
- Environment `APP_VERSION_CONFIG_PATH` supports
- Methods `->major()`, `->minor()`, `->patch()`, `->build()`, `->setBuild()`, `->refresh()` from `AvtoDev\AppVersion\AppVersionManager`
- Artisan command `version` argument `--refresh`

### Deprecated

- Helpers `app_version`, `app_build` and `app_version_hash`

## v2.1.0

### Changed

- Maximal `illuminate/*` packages version now is `6.*`

### Added

- GitHub actions for a tests running

## v2.0.1

### Fixed

- DI binding (register using interface instead implementation)

## v2.0.0

### Added

- Docker-based environment for development
- Project `Makefile`

### Changed

- Minimal `PHP` version now is `^7.1.3`
- Minimal `Laravel` version now is `5.5.x`
- Maximal `Laravel` version now is `5.8.x`
- Dependency `laravel/framework` changed to `illuminate/*`
- Composer scripts
- `\AvtoDev\AppVersion\AppVersionServiceProvider` &rarr; `AvtoDev\AppVersion\ServiceProvider`
- `AppVersionManagerContract` method signatures
- Helpers (`app_versio`, `app_build`, `app_version_hash`) type-hints

### Removed

- DI binding `app.version.manager`

## v1.4.0

### Changed

- Maximal PHP version now is undefined
- Maximal Laravel version now is `5.7.*`
- CI changed to [Travis CI][travis]
- [CodeCov][codecov] integrated

[travis]:https://travis-ci.org/
[codecov]:https://codecov.io/

## v1.3.1

### Fixed

- Getter for blade compiler [Issue #3][issue-3]

[issue-3]:https://github.com/avto-dev/app-version-laravel/issues/3

## v1.3.0

### Changed

- Up maximal `laravel` package version (`5.7.*`)
- Tests and CI config for `laravel` `5.7.*`

## v1.2.1

### Changed

- Composer dependencies clean-up
- Source and tests a little bit refactored
- Blade directives use contract instead abstract container bind alias
- Abstract tests bootstrapper class removed
- PHPUnit HTML coverage report disabled by default ([container errors](https://github.com/laravel/framework/issues/10808) after `composer update --no-interaction --prefer-lowest`)

## v1.2.0

### Fixed

- Fix bug with stored in file build value [#1]

### Removed

- Method `->clearCompiled()` removed *(without previous depreciation)*

## v1.1.0

### Added

- Method `->hashed()` now available with manager, helper and blade
- Alias `->version()` for method `->formatted()`

[keepachangelog]:https://keepachangelog.com/en/1.0.0/
[semver]:https://semver.org/spec/v2.0.0.html
