# laranail/license-verifier-ui

[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

> The core UI engine for [`laranail/license-verifier`](https://opensource.simtabi.com/license-verifier/):
> a **generator** that scaffolds owned, themeable preset packages (Blade, Livewire, Filament, Vue)
> which **extend this core**.

Instead of publishing scattered views and config, you install **one preset at a time** and the
generator writes a small, self-contained Composer package into your app — under **your namespace,
at your path, in the CSS framework you choose**. The generated package is thin (it subclasses this
core), so you own a tiny surface while all the logic and updates live here.

## How it works

```text
laranail/license-verifier-ui          ← core engine + generator + commands (this package)
└── presets/
    ├── …-ui-blade      ┐  each preset package extends the core and ships its
    ├── …-ui-livewire   │  own stubs + themes; install it, then generate an
    ├── …-ui-filament   │  owned package the runtime auto-discovers via composer.
    └── …-ui-vue        ┘
```

## Install

Require the preset you want (this pulls the core):

```bash
composer require laranail/license-verifier-ui-blade   # or -livewire / -filament / -vue
```

Then generate your owned package:

```bash
php artisan laranail::license-verifier-ui.install blade
```

You'll be prompted for:

| Prompt | Notes |
|--------|-------|
| **Theme** | `tailwind` (default), `bootstrap`, `alpine`, `unstyled`, `custom` (Filament uses its own design system) |
| **Composer name** | `vendor/package`, default `<app-vendor>/license-verifier-blade` |
| **Namespace** | derive from the `vendor/package` (e.g. `acme/license-verifier-blade` → `Acme\LicenseVerifierBlade`) or enter your own — **validated** (StudlyCase, ≥2 segments, no reserved words) |
| **Path** | where to write the package — **required, no default**, validated (relative, inside the app, writable, not a protected dir) |
| **Register mode** | *register via composer now* (default) or *generate only* |

On *register*, the command adds a path repository + `require` to your root `composer.json` and runs
`composer` for you. The generated package's service provider is then auto-discovered.

## Themes

Each HTML preset ships ready-to-use theme variants:

| Preset | Themes |
|--------|--------|
| Blade | tailwind, bootstrap, alpine, unstyled, custom |
| Livewire | tailwind, bootstrap, unstyled, custom |
| Vue | tailwind, bootstrap, unstyled, custom |
| Filament | filament (its own design system) |

`unstyled` is semantic HTML with `.lv-*` hook classes; `custom` is the same skeleton with `TODO`
markers for your design system.

## Commands

| Command | What |
|---------|------|
| `laranail::license-verifier-ui.install [preset]` | Generate + (optionally) register one preset package |
| `laranail::license-verifier-ui.uninstall` | Composer-remove a generated package; keep files by default (`--delete-files` to remove) |
| `laranail::license-verifier-ui.list` | List installed preset packages and their themes |

Flags on `install`: `--force` (overwrite target), `--no-symlink` (copy instead of symlink),
`--no-install` (generate files only).

## What gets generated

```
<your-path>/
├── composer.json        # requires laranail/license-verifier-ui-<preset>; PSR-4 = your namespace
├── src/Providers/<Preset>PresetServiceProvider.php   # extends the base provider (auto-discovered)
├── src/…                # thin controllers / components that extend the base
├── routes/ config/      # Blade & Vue
└── resources/views|js/  # your chosen theme — ready to use, yours to edit
```

## Documentation

| Page | What |
|------|------|
| [Installation](docs/installation.md) | Requiring a preset + running the generator |
| [Architecture](docs/architecture.md) | Core engine, generator, registry, base classes |
| [Themes](docs/themes.md) | The theme matrix and how to add one |
| [Configuration](docs/configuration.md) | Generated config keys + validation |
| [Blade](docs/presets/blade.md) · [Livewire](docs/presets/livewire.md) · [Filament](docs/presets/filament.md) · [Vue](docs/presets/vue.md) | Per-preset notes |

## Development

This repo is a **monorepo whose root is the core package**; the preset packages live under
`presets/*` as path sub-packages. Run the suite from the root:

```bash
composer test
```

Part of the [laranail licensing ecosystem](https://opensource.simtabi.com/license-verifier/).
