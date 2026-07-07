# Getting started

A guided walkthrough: require one preset package, generate your owned UI package, and render it.

Instead of publishing scattered views and config, you install one preset at a time and the
generator writes a small, self-contained Composer package into your app — under your namespace,
at your path, in the CSS framework you choose. The generated package is thin (it subclasses this
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

## 1. Require a preset

Require the preset you want (this pulls the core; a Blade install never downloads the
Vue/Filament/Livewire code):

```bash
composer require laranail/license-verifier-ui-blade   # or -livewire / -filament / -vue
```

## 2. Generate your owned package

```bash
php artisan laranail::license-verifier-ui.install blade
```

Answer the prompts — theme, composer name, namespace, path, register mode (all validated; see
[Generator commands](tools/generator.md) for the full prompt and flag reference). In *register*
mode the command wires the path repository + `require` into your root `composer.json` and runs
composer; the generated provider is auto-discovered.

## 3. Use it

- **Blade** — visit the `license/unlicensed` page, or `@include('license-verifier-blade::license-form')`.
- **Livewire** — `<livewire:lv-activation-form />`, `<livewire:lv-status-widget />`.
- **Filament** — add the generated `LicenseVerifierPlugin::make()` to your PanelProvider.
- **Vue** — import the SFC into your Vite entry and build (`registerLicenseVerifier(app)`).

Per-preset detail: [Blade](tools/blade-preset.md) · [Livewire](tools/livewire-preset.md) ·
[Filament](tools/filament-preset.md) · [Vue](tools/vue-preset.md).

## Next steps

- [Installation](installation.md) — the install/uninstall reference.
- [Themes](tools/themes.md) — the theme matrix and how to add one.
- [Configuration](configuration.md) — generator defaults + generated package config.
- [Architecture](architecture.md) — core engine, generator, registry, base classes.

---

[← Docs index](../README.md#documentation)
