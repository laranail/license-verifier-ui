# Filament preset

A Filament plugin exposing a license management **Page** and a status
**Widget**. Activates when `filament/filament` (`^4.0 || ^3.2`) is installed.

## Register

```php
use Simtabi\Laranail\Licence\Verifier\Presets\Filament\Plugin\LicenseVerifierPlugin;

public function panel(Panel $panel): Panel
{
    return $panel->plugin(LicenseVerifierPlugin::make());
}
```

The plugin id is `license-verifier`. The page sits in the `Settings` navigation
group (configurable) and shows the license status plus a driver-aware
activation form; the widget renders the current status.

## Config

```bash
php artisan vendor:publish --tag=laranail::license-verifier-filament-preset-config
```

Key: `navigation_group` (env `LICENSE_VERIFIER_FILAMENT_GROUP`).

## Views

```bash
php artisan vendor:publish --tag=laranail::license-verifier-filament-preset-views
```

[← Docs index](../README.md#documentation)
