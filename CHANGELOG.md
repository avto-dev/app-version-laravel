# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog][keepachangelog] and this project adheres to [Semantic Versioning][semver].

## v1.4.0

### Changed

- Maximal PHP version now is undefined
- Maximal Laravel version now is `5.7.*`
- CI changed to [Travis CI][travis]
- [CodeCov][codecov] integrated

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

### Updated

- Source and tests a little bit refactored
- Blade directives use contract instead abstract container bind alias
- Abstract tests bootstrapper class removed
- PHPUnit HTML coverage report disabled by default ([container errors](https://github.com/laravel/framework/issues/10808) after `composer update --no-interaction --prefer-lowest`)

## v1.2

### Fixed

- Fix bug with stored in file build value [#1]

### Removed

- Method `->clearCompiled()` removed *(without previous depreciation)*

## v1.1

### Added

- Method `->hashed()` now available with manager, helper and blade
- Alias `->version()` for method `->formatted()`

[keepachangelog]:https://keepachangelog.com/en/1.0.0/
[semver]:https://semver.org/spec/v2.0.0.html
