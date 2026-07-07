# laranail/license-verifier-ui

[![Latest version on Packagist](https://img.shields.io/packagist/v/laranail/license-verifier-ui.svg)](https://packagist.org/packages/laranail/license-verifier-ui)
[![Tests](https://github.com/laranail/license-verifier-ui/actions/workflows/tests.yml/badge.svg)](https://github.com/laranail/license-verifier-ui/actions/workflows/tests.yml)
[![Static analysis](https://github.com/laranail/license-verifier-ui/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/laranail/license-verifier-ui/actions/workflows/static-analysis.yml)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

> The core UI engine for [`laranail/license-verifier`](https://opensource.simtabi.com/documentation/laranail/license-verifier/) — a generator that scaffolds owned, themeable preset packages (Blade, Livewire, Filament, Vue) into your app under your namespace, at your path, in the CSS framework you choose. The generated preset subclasses this core, so you own a tiny surface while the logic and updates live here.

Requires PHP `^8.4.1 || ^8.5` on Laravel `^13`.

## Install

Require the preset you want — each is its own package that pulls this core automatically:

```bash
composer require laranail/license-verifier-ui-blade   # or -livewire / -filament / -vue
```

## Quick start

Generate your owned preset package, then render it:

```bash
php artisan laranail::license-verifier-ui.install blade
```

Answer the prompts (theme, composer name, namespace, path, register mode) and the generator
writes a small, self-contained Composer package into your app — auto-discovered, ready to use:

```blade
@include('license-verifier-blade::license-form')
```

Full tour: [Getting started](docs/getting-started.md).

## Presets & themes

Four presets ship as sub-packages of this monorepo, each with ready-to-use theme variants:

| Preset | Package | Themes |
|--------|---------|--------|
| Blade | `laranail/license-verifier-ui-blade` | tailwind, bootstrap, alpine, unstyled, custom |
| Livewire | `laranail/license-verifier-ui-livewire` | tailwind, bootstrap, unstyled, custom |
| Vue | `laranail/license-verifier-ui-vue` | tailwind, bootstrap, unstyled, custom |
| Filament | `laranail/license-verifier-ui-filament` | filament (its own design system) |

`unstyled` is semantic HTML with `.lv-*` hook classes; `custom` is the same skeleton with `TODO`
markers for your design system. See [Themes](docs/tools/themes.md).

## <a name="documentation"></a>Documentation

Full documentation is at **[opensource.simtabi.com/documentation/laranail/license-verifier-ui](https://opensource.simtabi.com/documentation/laranail/license-verifier-ui/)**.

### Guides

- [Installation](docs/installation.md) — requiring a preset + running the generator.
- [Getting started](docs/getting-started.md) — how it works, generate, and render.
- [Configuration](docs/configuration.md) — generator defaults, generated config keys, validation.
- [Architecture](docs/architecture.md) — core engine, generator, registry, base classes.
- [Release](docs/release.md) — tag-driven monorepo releases and versioning policy.

### Reference

- [Generator commands](docs/tools/generator.md) — `install` / `uninstall` / `list` / `doctor`, prompts, flags, what gets generated.
- [Themes](docs/tools/themes.md) — the theme matrix and how to add one.
- [Blade preset](docs/tools/blade-preset.md) — routes, controller, views, progressive-enhancement JS.
- [Livewire preset](docs/tools/livewire-preset.md) — the activation-form + status-widget components.
- [Filament preset](docs/tools/filament-preset.md) — the panel plugin, page, and widget.
- [Vue preset](docs/tools/vue-preset.md) — the SFC, JSON endpoints, and Vite entry.

### Project

- [Changelog](CHANGELOG.md) — release history.

## Stability

Pre-1.0 (`0.x`) — the public API may change between minor versions. Pin a version before bumping.

## Local development

This repo is a **monorepo whose root is the core package**; the preset packages live under
`presets/*` as path sub-packages, and a single suite covers everything. Run it from the root:

```bash
composer test     # Pest
composer lint     # pint --test + phpstan + rector --dry-run
```

## Sister packages

- [`laranail/license-verifier`](https://opensource.simtabi.com/documentation/laranail/license-verifier/) — the headless verification client these UIs front.
- [`laranail/license-kit`](https://opensource.simtabi.com/documentation/laranail/license-kit/) — the self-hosted PASETO/Ed25519 licensing server (issuer + seat registry).
- [`laranail/product-updater`](https://opensource.simtabi.com/documentation/laranail/product-updater/) — license-gated product updates.
- [`laranail/demo-mode`](https://opensource.simtabi.com/documentation/laranail/demo-mode/) — demo/sandbox restrictions for licensed products.

## Community

- [Issues](https://github.com/laranail/license-verifier-ui/issues) — bugs and feature requests.

## Contributing & security

Issues and PRs are welcome — see [CONTRIBUTING.md](CONTRIBUTING.md). Report vulnerabilities per
[SECURITY.md](SECURITY.md) (opensource@simtabi.com); participation follows the [Code of Conduct](CODE_OF_CONDUCT.md).

## License

MIT © Simtabi LLC. See [LICENSE](LICENSE).
