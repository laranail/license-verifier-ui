# Changelog

All notable changes to `laranail/license-verifier-ui` are documented in this file.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/)
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Changed

- **Newly generated packages name their routes `laranail-license-verifier-ui.*`** (Blade) and
  `laranail-license-verifier-ui-vue.*` (Vue), instead of `license-verifier.*` and
  `license-verifier-vue.*`. Route names share one flat registry with the host application and
  every other package, so a bare prefix could be silently replaced. Only new output changes: a
  package generated earlier carries the old prefix in its own config and views, and the base
  providers still fall back to it. `GeneratedPackage::legacyRouteNamePrefix()` names it.

- The PHP floor is `^8.4.1`, up from `^8.4`. `laranail/package-tools` and `laranail/console`
  are `^8.4.1`, so a resolver that took the manifest at its word and pinned the platform to
  8.4.0 could not install them. Dependabot does exactly that, and had been failing on it.

### Fixed

- The end-to-end boot tests register each generated provider and then refresh the route name
  lookups, as Laravel does after boot. They registered it after boot without refreshing, and
  passed only while nothing had resolved `url` first; on a fresh dependency set seven went red
  with `Route [license-verifier.activate] not defined`.

## [0.1.0] - 2026-07-11

Initial public release.

[Unreleased]: https://github.com/laranail/license-verifier-ui/compare/v0.1.0...HEAD
