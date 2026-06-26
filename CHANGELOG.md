# Changelog

All notable changes to `laranail/license-verifier-ui` are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Single package bundling the Blade, Filament, Livewire and Vue UI presets for
  `laranail/license-verifier`, each as a self-contained module under `src/<Preset>/`.
- Auto-activation: a preset boots when its framework is present (Blade and Vue are
  always available); override per preset via `config/license-verifier-ui.php` or the
  `LICENSE_VERIFIER_PRESET_*` env vars.
- `laranail::license-verifier-ui.install` command to interactively publish chosen presets.

### Changed

- Supersedes the four separate packages `laranail/license-verifier-{blade,filament,livewire,vue}-preset`.
  Config keys, view namespaces and per-preset publish tags are unchanged, so apps that
  published from the old packages keep working. The npm package
  `@laranail/license-verifier-vue-preset` is now `@laranail/license-verifier-ui`.
