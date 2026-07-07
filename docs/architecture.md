# Architecture

How the core engine, the preset packages, and the generated package relate.

## Monorepo, root = core

This repository **is** the published core package `laranail/license-verifier-ui`, and also the
monorepo root. The four preset packages are publishable path sub-packages under `presets/*`. The
root `composer.json` declares dev-only path repositories to `presets/*` and `require-dev`s the
preset packages so a single test suite covers everything. Consumers of the published core ignore
those dev `repositories`/`require-dev`.

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
