# laranail/license-verifier-ui

[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE.md)

> Blade, Filament, Livewire and Vue UI presets for
> [`laranail/license-verifier`](https://opensource.simtabi.com/license-verifier/),
> in one package. Install only the presets you need.

Each preset is a driver-aware, fully overridable UI for the headless
license-verifier client. They live side by side in this single package and
**auto-activate when their framework is present** — so a Filament app gets the
Filament page, a Livewire app gets the components, and so on, with no wiring.

## Install

```bash
composer require laranail/license-verifier-ui
```

Requires `laranail/license-verifier`, PHP `^8.4 || ^8.5`, Laravel `^13`.
`filament/filament` and `livewire/livewire` are optional — install them only if
you use those presets (Blade and Vue need no extra PHP dependency).

Then publish the preset(s) you want interactively:

```bash
php artisan laranail::license-verifier-ui.install
```

The command lists only the presets whose framework is installed and publishes
the selected presets' config, views and assets.

## Presets at a glance

| Preset | Activates when | Ships |
|--------|----------------|-------|
| **Blade** | always | Driver-aware Blade views (activation form, modal, unlicensed page, settings panel, status widget), vanilla JS, and `license/*` routes. |
| **Filament** | `filament/filament` installed | A license management `Page` and a status `Widget`, exposed as a Filament plugin. |
| **Livewire** | `livewire/livewire` installed | `lv-activation-form` and `lv-status-widget` Livewire components. |
| **Vue** | always (Vue via npm) | A publishable `LicenseForm.vue` SFC, an `entry.js`, and JSON `license/*` endpoints. |

### Blade

```blade
@include('license-verifier-blade::license-form', [
    'fields' => app(\Simtabi\Laranail\Licence\Verifier\Drivers\DriverManager::class)->active()->activationFields(),
])
```

Publish/override views, config and JS:

```bash
php artisan vendor:publish --tag=laranail::license-verifier-blade-preset-views
php artisan vendor:publish --tag=license-verifier-blade-assets
```

### Filament

```php
use Simtabi\Laranail\Licence\Verifier\Presets\Filament\Plugin\LicenseVerifierPlugin;

$panel->plugin(LicenseVerifierPlugin::make());
```

### Livewire

```blade
<livewire:lv-activation-form />
<livewire:lv-status-widget />
```

### Vue

```js
import { registerLicenseVerifier } from '@laranail/license-verifier-ui'

registerLicenseVerifier(app)
```

```bash
php artisan vendor:publish --tag=license-verifier-vue-assets
```

## Choosing which presets are active

Publish the umbrella config and toggle presets in
`config/license-verifier-ui.php`:

```bash
php artisan vendor:publish --tag=license-verifier-ui-config
```

```php
'presets' => [
    // null  => auto (active when the framework is present)
    // false => hard-disabled
    // true  => force-enabled (still needs the framework)
    'filament' => ['enabled' => false, 'publishable' => true],
],
```

or via env: `LICENSE_VERIFIER_PRESET_FILAMENT=false`.

## Documentation

| Page | What |
|------|------|
| [Blade preset](docs/blade.md) | Views, routes, JS, overriding |
| [Filament preset](docs/filament.md) | Plugin, page, widget |
| [Livewire preset](docs/livewire.md) | Components and aliases |
| [Vue preset](docs/vue.md) | SFC, endpoints, npm package |
| [Configuration](docs/configuration.md) | Activation gating & publish tags |

## Development

```bash
composer test
```

Part of the [laranail licensing ecosystem](https://opensource.simtabi.com/license-verifier/).
