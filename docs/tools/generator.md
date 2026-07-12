# Generator commands

Four Artisan commands, namespaced `laranail::license-verifier-ui.*`, drive the preset generator.

| Command | What |
|---------|------|
| `laranail::license-verifier-ui.install [preset]` | Generate + (optionally) register one preset package |
| `laranail::license-verifier-ui.uninstall` | Composer-remove a generated package; keep files by default (`--delete-files` to remove) |
| `laranail::license-verifier-ui.list` | List installed preset packages and their themes |
| `laranail::license-verifier-ui.doctor {--json}` | Diagnose the generator setup |

## `install`

```bash
php artisan laranail::license-verifier-ui.install blade   # or livewire / filament / vue
```

You'll be prompted for:

| Prompt | Notes |
|--------|-------|
| Theme | `tailwind` (default), `bootstrap`, `alpine`, `unstyled`, `custom` (Filament uses its own design system) — see [Themes](themes.md) |
| Composer name | `vendor/package`, default `<app-vendor>/license-verifier-<preset>` |
| Namespace | derive from the `vendor/package` (e.g. `acme/license-verifier-blade` → `Acme\LicenseVerifierBlade`) or enter your own — validated (StudlyCase, ≥2 segments, no reserved words) |
| Path | where to write the package — required, no default, validated (relative, inside the app, writable, not a protected dir) |
| Register mode | *register via composer now* (default) or *generate only* |

On *register*, the command adds a path repository + `require` to your root `composer.json` and
runs `composer` for you. The generated package's service provider is then auto-discovered.

Flags: `--force` (overwrite an existing target directory), `--no-symlink` (copy the package
instead of symlinking the path repository), `--no-install` (generate files only; do not run
composer — the manual composer commands are printed).

## `uninstall`

```bash
php artisan laranail::license-verifier-ui.uninstall                 # composer-removes; keeps files
php artisan laranail::license-verifier-ui.uninstall --delete-files  # also deletes the package directory
```

## What gets generated

```
<your-path>/
├── composer.json        # requires laranail/license-verifier-ui-<preset>; PSR-4 = your namespace
├── src/Providers/<Preset>PresetServiceProvider.php   # extends the base provider (auto-discovered)
├── src/…                # thin controllers / components that extend the base
├── routes/ config/      # Blade & Vue
└── resources/views|js/  # your chosen theme — ready to use, yours to edit
```

The generated package is thin — it subclasses the core's base classes, so you own a tiny surface
while the logic and updates live in the core (see [Architecture](../architecture.md)). Per-preset
detail: [Blade](blade-preset.md) · [Livewire](livewire-preset.md) ·
[Filament](filament-preset.md) · [Vue](vue-preset.md).

---

[← Docs index](../../README.md#documentation)
