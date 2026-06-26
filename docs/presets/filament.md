# Filament preset

`composer require laranail/license-verifier-ui-filament` then
`php artisan laranail::license-verifier-ui.install filament`.

Theme: `filament` (Filament's own design system) — no CSS-framework choice.

## What's generated

- `src/Providers/<Ns>PresetServiceProvider` — registers config + views.
- `src/LicenseVerifierPlugin` — extends the base plugin.
- `src/Filament/Pages/LicensePage`, `src/Filament/Widgets/LicenseStatusWidget` — extend the bases.
- `config/license-verifier-filament.php`, `resources/views/{pages/license,widgets/status}.blade.php`.

## Use

Add the plugin to your panel (the install command prints the exact line):

```php
use <Your\Namespace>\LicenseVerifierPlugin;

public function panel(Panel $panel): Panel
{
    return $panel->plugin(LicenseVerifierPlugin::make());
}
```

[← Docs index](../../README.md#documentation)
