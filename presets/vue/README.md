# laranail/license-verifier-ui-vue

[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

> Vue 3 UI preset for [`laranail/license-verifier-ui`](https://opensource.simtabi.com/documentation/laranail/license-verifier-ui/).

```bash
composer require laranail/license-verifier-ui-vue
php artisan laranail::license-verifier-ui.install vue
```

Generates an owned Vue 3 `LicenseForm` SFC + entry point + JSON endpoints (extending this package)
into your app, with a `package.json` for npm/Vite. Themes: **tailwind, bootstrap, unstyled,
custom**. Build the JS with Vite and mount with `mountLicenseVerifier('#lv-license-app')`.

Requires PHP `^8.4 || ^8.5`, Laravel `^13`, Vue `^3.4` (npm), and `laranail/license-verifier-ui`.
See the [ecosystem docs](https://opensource.simtabi.com/documentation/laranail/license-verifier-ui/docs/).
