# Changelog

All Notable changes to `http-transitions` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## [v1.0.0] - Unreleased

### Changed
- Requires PHP 8.3+ and Laravel 11, 12 or 13
- Modernized codebase: strict types, readonly classes, constructor promotion, typed properties
- `Config::headerKey()` replaced by the readonly `Config::$headerKey` property
- `Config::setHeaderKey()` / `Config::setTransitions()` removed; `Config` is immutable
- `LaravelTransitionFactory` caches transitions per factory instance instead of statically across all instances
- Config publish tag is now `transitions-config`
- Versions are compared with natural ordering (`strnatcmp`): `20150101` < `20160101`, `2015-06-01` < `2016-01-01`, `v2` < `v10`; non-numeric words like `latest` sort after numeric versions
- Numeric equivalence is no longer honoured (`2.0` no longer equals `2`), and mixed formats compare differently than before; the version header and config keys must share one format
- An empty `Api-Version` header is treated the same as a missing one: every transition applies
- `Config`'s constructor now takes `list<Stage>`; build it from the config array with `Config::fromArray()`
- `Config`, `TransitionMiddleware`, `LaravelTransitionFactory`, `TransitionProvider` and `GenerateTransition` are now `final`
- `Config::transitionsForVersion()` replaced by `Config::transitionsFor(?Version)`; config is parsed into `Stage` / `Version` value objects
- The transitions config is validated when first resolved: non-list stages and classes that don't extend `Transition` throw `InvalidConfiguration`
- `HeaderKeyNotDefined` replaced by `InvalidConfiguration::missingHeaderKey()`
- `Config` is bound as a singleton
- Generator stubs use `{{ namespace }}` / `{{ class }}` placeholders

### Removed
- Travis CI, Scrutinizer and StyleCI configs (replaced by GitHub Actions, Pint and PHPStan at max level)

## [v0.4.0] - 2018-10-26

### Added
- extracted `TransitionFactory` interface
- updated Laravel service provider to use `LaravelTransitionFactory`
- added wildcard (`*`) option for applying transitions

### Deprecated
- Nothing

### Fixed
- Nothing

### Removed
- Nothing

### Security
- Nothing

## [v0.2.0] - 2017-09-18

### Added
- Console commands to generate transition classes.

### Deprecated
- Nothing

### Fixed
- Nothing

### Removed
- Nothing

### Security
- Nothing
