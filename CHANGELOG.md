# Changelog

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
