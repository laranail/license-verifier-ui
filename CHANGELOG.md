# Changelog

All notable changes to `laranail/license-verifier-ui` are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- **Core UI engine + preset generator.** `laranail/license-verifier-ui` is now the core that
  published presets extend and use. It ships a stub-based generator that scaffolds an owned,
  thin preset package into the host app under a user-chosen namespace, path and theme, then
  registers it via composer.
- **Per-preset packages** under `presets/*`, each extending the core:
  `laranail/license-verifier-ui-{blade,livewire,filament,vue}`. Installing one pulls only the
  core + that preset.
- **Multi-CSS-UI themes**: tailwind, bootstrap, alpine, unstyled and custom for the HTML presets
  (Filament uses its own design system).
- **Commands** `laranail::license-verifier-ui.{install,uninstall,list}`. Install prompts for
  theme + composer `vendor/package` + namespace + path and a register mode; the namespace can be
  derived from the `vendor/package` (e.g. `acme/license-verifier-blade` → `Acme\LicenseVerifierBlade`)
  or entered explicitly, and namespace/path are validated harshly; uses `ManagesComposer` for
  path-repo registration / removal.
- Generated (and shipped) service providers live under a `Providers/` sub-namespace
  (`<Namespace>\Providers\<Preset>PresetServiceProvider`), wired in each `composer.json`'s
  `extra.laravel.providers`.
- Every generated config key is honored end to end — the Blade `redirect_after_activation` is
  surfaced on the activation form (and acted on by the script, incl. the Alpine component), and the
  Filament `navigation_group` drives the generated page's navigation group.

### Changed

- Replaces the previous single merged package (and the four original
  `laranail/license-verifier-*-preset` packages). Publishing is now generation: a real, owned,
  composer-registered package per preset, rather than scattered `vendor:publish` output.
