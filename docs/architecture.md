# Architecture

How the core engine, the preset packages, and the generated package relate.

## Monorepo, root = core

This repository **is** the published core package `laranail/license-verifier-ui`, and also the
monorepo root. The four preset packages are publishable sub-packages under `presets/*`, each with
its own `composer.json`. The root `composer.json` maps each preset's namespace to its `src/` in
`autoload-dev` (PSR-4), so a single test suite covers everything. The test case registers the
preset service providers itself. Consumers of the published core ignore `autoload-dev`.

The presets are deliberately **not** wired in as `path` repositories under `require-dev`. Each
preset requires `laranail/license-verifier-ui ^0.1` back, so that setup only resolves when Composer
is told the root's version (`COMPOSER_ROOT_VERSION`). Dependabot cannot set it, sees the root as
`1.0.0`, and fails with `dependency_file_not_resolvable`. Loading the source through
`autoload-dev` needs no root version at all. The preset's own dependencies (`filament/filament`,
`livewire/livewire`) are in the root `require-dev` for the same reason.

## Core (`src/`)

| Area | Responsibility |
|------|----------------|
| `Generators/` | `PresetPackageGenerator` renders a preset's scaffold (composer.json/README/.gitignore + PHP/routes/config) + the chosen theme into a destination; `StubRenderer` substitutes `$TOKEN$` placeholders; `TokenSet` builds the token map; `GeneratedPackage` is the value object of the user's choices. |
| `Presets/` | `PresetRegistry` (singleton) collects a `PresetDefinition` from each installed preset package; `Contracts/PresetContributor`. |
| `Themes/` | `Theme` keys + labels. |
| `Validation/` | `NamespaceValidator` (PSR-4, ≥2 segments, no reserved words) and `PathValidator` (`PathResolver::validatePathSecurity` + inside `base_path()` + writable + not protected). |
| `Http/`, `Rendering/`, `Providers/`, `Support/` | The base classes generated packages extend: `BaseLicenseController`, `ActivateLicenseRequest`, `FieldRenderer`, `BasePresetServiceProvider`, `AuthorizesLicenseActions`. |
| `Console/` | `InstallPresetCommand`, `UninstallPresetCommand`, `ListPresetsCommand`. |

## Preset packages (`presets/<preset>/`)

Each carries: a `Providers/…ServiceProvider` that registers its `PresetDefinition` into the core
registry; its family base classes (e.g. `BaseBladeLicenseController`, `BaseBladePresetServiceProvider`);
and one tidy `stubs/` folder — `scaffold/` (composer.json/README/.gitignore + PHP/routes/config)
and `themes/<theme>/` (views/js). There is no shared root `stubs/` folder.

## Stub tokens

Stubs use `$UPPER_SNAKE$` placeholders (e.g. `$PHP_NAMESPACE$`, `$VIEW_NAMESPACE$`,
`$CONFIG_KEY$`, `$ROUTE_NAME_PREFIX$`) — deliberately **not** `{{ }}`, so view stubs can carry
real Blade alongside generator tokens. The generator asserts no placeholder is left unresolved.

## Generated package

A thin, owned package: its provider extends the base provider (and is auto-discovered via
`extra.laravel.providers`), its controllers/components subclass the base classes, and its
`resources/` holds the chosen theme. The base classes — and their updates — stay in the core.

---

[← Docs index](../README.md#documentation)
