# Configuration

## Activation gating

The umbrella config `config/license-verifier-ui.php` controls which presets the
package boots:

```bash
php artisan vendor:publish --tag=license-verifier-ui-config
```

```php
'presets' => [
    'blade'    => ['enabled' => null,  'publishable' => true],
    'filament' => ['enabled' => null,  'publishable' => true],
    'livewire' => ['enabled' => null,  'publishable' => true],
    'vue'      => ['enabled' => null,  'publishable' => true],
],
```

For each preset, `enabled`:

- `null` — **auto**: active only when the preset's framework is installed
  (Blade and Vue are framework-free, so they are always active).
- `false` — hard-disabled, even when the framework is present.
- truthy — force-enabled (still requires the framework to be installed).

`publishable` controls whether the preset is offered by
`php artisan laranail::license-verifier-ui.install`.

Each toggle also reads an env var: `LICENSE_VERIFIER_PRESET_{BLADE,FILAMENT,
LIVEWIRE,VUE}`.

## Publish tags

| Preset | Config | Views | Assets |
|--------|--------|-------|--------|
| Blade | `laranail::license-verifier-blade-preset-config` | `laranail::license-verifier-blade-preset-views` | `license-verifier-blade-assets` |
| Filament | `laranail::license-verifier-filament-preset-config` | `laranail::license-verifier-filament-preset-views` | — |
| Livewire | `laranail::license-verifier-livewire-preset-config` | `laranail::license-verifier-livewire-preset-views` | — |
| Vue | `laranail::license-verifier-vue-preset-config` | `laranail::license-verifier-vue-preset-views` | `license-verifier-vue-assets` |
| Umbrella | `license-verifier-ui-config` | — | — |

[← Docs index](../README.md#documentation)
