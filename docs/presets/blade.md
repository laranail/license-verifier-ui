# Blade preset

`composer require laranail/license-verifier-ui-blade` then
`php artisan laranail::license-verifier-ui.install blade`.

Themes: tailwind, bootstrap, alpine, unstyled, custom.

## What's generated

- `src/Providers/<Ns>PresetServiceProvider` — loads config/views and the gated route group.
- `src/Http/Controllers/LicenseController` — extends `BaseBladeLicenseController`.
- `routes/web.php` — `unlicensed`, `status`, `activate`, `deactivate`, `reminder/skip`
  (under the configured prefix; route names use the `routes.name` prefix).
- `config/license-verifier-blade.php`, `resources/views/*` (chosen theme), `resources/js/license-verifier.js`.

## Use

```blade
@include('license-verifier-blade::license-form')
```

The `license/unlicensed` page is a full, styled activation screen. The form posts to the JSON
`activate` endpoint via the bundled JS (progressive enhancement — it still submits without JS).

[← Docs index](../../README.md#documentation)
