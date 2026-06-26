# Vue preset

A publishable Vue 3 `LicenseForm` SFC plus JSON endpoints. The PHP side is
always active; the component is consumed via npm.

## Endpoints

Registered under the `license` prefix when `license-verifier-vue.routes.enabled`
is true: `GET license/status`, `POST license/activate`,
`POST license/deactivate` (route names `license-verifier-vue.*`).

## Component

The npm package is `@laranail/license-verifier-ui`:

```js
import { registerLicenseVerifier } from '@laranail/license-verifier-ui'

registerLicenseVerifier(app)
```

Publish the SFC + entry point into your app to customise:

```bash
php artisan vendor:publish --tag=license-verifier-vue-assets
```

Publishes `entry.js` and `components/LicenseForm.vue` to
`resources/js/vendor/license-verifier`.

## Config

```bash
php artisan vendor:publish --tag=laranail::license-verifier-vue-preset-config
```

Keys: `routes.{enabled,prefix,middleware}`, `permission`.

[← Docs index](../README.md#documentation)
