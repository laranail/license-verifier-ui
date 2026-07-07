# Installation

Requirements, requiring a preset package, and generating your owned UI package.

Requires PHP `^8.4.1 || ^8.5` on Laravel `^13`, with
[`laranail/license-verifier`](https://opensource.simtabi.com/documentation/laranail/license-verifier/)
pulled in automatically.

## 1. Require a preset package

Each preset is its own package that depends on the core. Require only what you need:

```bash
composer require laranail/license-verifier-ui-blade
# or -livewire / -filament / -vue
```

This pulls `laranail/license-verifier-ui` (the core) automatically. A Blade install never
downloads the Vue/Filament/Livewire code.

## 2. Generate an owned preset package

```bash
php artisan laranail::license-verifier-ui.install blade
```

Answer the prompts (theme, namespace, composer name, path, register mode). The generator writes
a package to your chosen path and — in *register* mode — adds a path repository + `require` to
your root `composer.json` and runs composer. The package's provider is auto-discovered.

Flags: `--force` (overwrite the target), `--no-symlink` (copy instead of symlink the path repo),
`--no-install` (write files only and print the manual composer commands). Full prompt + flag
reference: [Generator commands](tools/generator.md).

## 3. Use it

- **Blade** — visit the `license/unlicensed` page, or `@include('license-verifier-blade::license-form')`.
- **Livewire** — `<livewire:lv-activation-form />`, `<livewire:lv-status-widget />`.
- **Filament** — add `\<Your\Namespace>\LicenseVerifierPlugin::make()` to your PanelProvider.
- **Vue** — import the SFC into your Vite entry and build (`registerLicenseVerifier(app)`).

## Uninstall

```bash
php artisan laranail::license-verifier-ui.uninstall          # composer-removes; keeps files
php artisan laranail::license-verifier-ui.uninstall --delete-files
```

---

[← Docs index](../README.md#documentation)
