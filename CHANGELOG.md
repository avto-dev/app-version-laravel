# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog][keepachangelog] and this project adheres to [Semantic Versioning][semver].

## v3.0.0

### Added

- PHP `7.4` supports

### Changed

- Config file location (`./src/config/version.php` &rarr; `./config/version.php`)

### Removed

- Facade `AvtoDev\AppVersion\AppVersionFacade`
- Environment `APP_VERSION_CONFIG_PATH` supports

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
