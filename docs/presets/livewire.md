# Livewire preset

`composer require laranail/license-verifier-ui-livewire` then
`php artisan laranail::license-verifier-ui.install livewire`.

Themes: tailwind, bootstrap, unstyled, custom (Livewire bundles Alpine, so no separate alpine theme).

## What's generated

- `src/Providers/<Ns>PresetServiceProvider` — registers the two Livewire components from config aliases.
- `src/Components/{ActivationForm,StatusWidget}` — extend the base components.
- `config/license-verifier-livewire.php`, `resources/views/livewire/*` (chosen theme).

## Use

```blade
<livewire:lv-activation-form />
<livewire:lv-status-widget />
```

Rename the aliases via `config/license-verifier-livewire.php` (`components.activation_form`,
`components.status_widget`).

[← Docs index](../../README.md#documentation)
