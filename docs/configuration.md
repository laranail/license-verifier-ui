# Configuration

## Core (generator defaults)

`config/license-verifier-ui.php` configures the install command — `default_theme` and
`composer_vendor` seed the prompts; `symlink` and `composer_timeout` control composer registration:

```php
'default_theme'     => 'tailwind',
'composer_vendor'   => null,        // seeds the vendor in the vendor/package prompt; null → your app's composer vendor
'symlink'           => true,
'composer_timeout'  => 300,
```

The PHP namespace is **not** configured here — at install time it is either derived from the
chosen `vendor/package` (e.g. `acme/license-verifier-blade` → `Acme\LicenseVerifierBlade`) or
entered explicitly.

## Generated package config

Each generated package ships its own `config/license-verifier-<preset>.php`, merged at the flat
key `license-verifier-<preset>` by its provider:

- **Blade / Vue** — `routes.{enabled,prefix,name,middleware}`, `permission`
  (a Gate ability, or null), and (Blade) `redirect_after_activation`.
- **Livewire** — `components.{activation_form,status_widget}` (the `<livewire:…>` aliases),
  `permission`.
- **Filament** — `navigation_group`.

## Harsh install-time validation

- **Namespace** must be PSR-4 StudlyCase with **≥2 segments** (so it can't shadow your app root)
  and contain no PHP reserved words.
- **Path** must be relative, resolve **inside `base_path()`**, have a writable parent, and not be
  a protected directory (`vendor/`, `node_modules/`, `storage/framework`, `.git`). A non-empty
  existing target is refused unless `--force`.

[← Docs index](../README.md#documentation)
