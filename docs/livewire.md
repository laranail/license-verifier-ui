# Livewire preset

Two Livewire components. Activates when `livewire/livewire` (`^3.5`) is
installed.

## Components

```blade
<livewire:lv-activation-form />
<livewire:lv-status-widget />
```

`ActivationForm` validates the license key and activates via the active driver;
`StatusWidget` renders the current verification status.

## Config

```bash
php artisan vendor:publish --tag=laranail::license-verifier-livewire-preset-config
```

Keys: `components.activation_form` (default `lv-activation-form`),
`components.status_widget` (default `lv-status-widget`), `permission`. The
component aliases above are read from config at registration time, so renaming
them re-registers under the new names.

## Views

```bash
php artisan vendor:publish --tag=laranail::license-verifier-livewire-preset-views
```

[← Docs index](../README.md#documentation)
