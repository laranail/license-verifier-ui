# Vue preset

`composer require laranail/license-verifier-ui-vue` then
`php artisan laranail::license-verifier-ui.install vue`.

Themes: tailwind, bootstrap, unstyled, custom.

## What's generated

- `src/Providers/<Ns>PresetServiceProvider` — registers the gated JSON route group.
- `src/Http/Controllers/LicenseController` — extends the core `BaseLicenseController`.
- `routes/web.php` — `status`, `activate`, `deactivate` (route names `laranail-license-verifier-ui-vue.*`).
- `config/license-verifier-vue.php`, `resources/views/mount.blade.php`,
  `resources/js/{entry.js, components/LicenseForm.vue}`, and a `package.json`.

> A package generated before 2026-10 names its routes `license-verifier-vue.*`. That prefix is
> written into its own `config/license-verifier-vue.php` and views, so it keeps working
> unchanged; regenerate, or set `routes.name` and update the views, to move to the scoped prefix.

## Use

Build the JS with Vite, then either register the component globally or mount it standalone:

```js
import { registerLicenseVerifier, mountLicenseVerifier } from '@your-vendor/license-verifier-vue'

registerLicenseVerifier(app)   // app.component('LicenseForm', …)
// or
mountLicenseVerifier('#lv-license-app')
```

Drop `@include('license-verifier-vue::mount')` where you want the form; it passes the endpoint
URLs + CSRF token as data attributes.

---

[← Docs index](../../README.md#documentation)
