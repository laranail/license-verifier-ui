# Blade preset

Dependency-free Blade + vanilla-JS UI. Always active (no framework required).

## Routes

Registered under the `license` prefix (configurable) when
`license-verifier-blade.routes.enabled` is true:

| Method | URI | Name | Purpose |
|--------|-----|------|---------|
| GET | `license/unlicensed` | `license-verifier.unlicensed` | Unlicensed page with the driver-aware form |
| GET | `license/status` | `license-verifier.status` | JSON license status |
| POST | `license/activate` | `license-verifier.activate` | Activate a license |
| POST | `license/deactivate` | `license-verifier.deactivate` | Deactivate |
| POST | `license/reminder/skip` | `license-verifier.reminder.skip` | Skip the reminder |

## Views

Namespace `license-verifier-blade::` — `license-form`, `activation-modal`,
`settings-panel`, `status-widget`, `unlicensed`. Publish to override:

```bash
php artisan vendor:publish --tag=laranail::license-verifier-blade-preset-views
```

## Assets

```bash
php artisan vendor:publish --tag=license-verifier-blade-assets
```

Publishes `license-verifier.js` to `public/vendor/license-verifier-blade`.

## Config

```bash
php artisan vendor:publish --tag=laranail::license-verifier-blade-preset-config
```

Keys: `routes.{enabled,prefix,name,middleware}`, `permission`,
`redirect_after_activation`.

[← Docs index](../README.md#documentation)
