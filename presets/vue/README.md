# laranail/license-verifier-ui-vue

[![Latest version on Packagist](https://img.shields.io/packagist/v/laranail/license-verifier-ui-vue.svg)](https://packagist.org/packages/laranail/license-verifier-ui-vue)
[![Tests](https://github.com/laranail/license-verifier-ui/actions/workflows/tests.yml/badge.svg)](https://github.com/laranail/license-verifier-ui/actions/workflows/tests.yml)
[![Static analysis](https://github.com/laranail/license-verifier-ui/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/laranail/license-verifier-ui/actions/workflows/static-analysis.yml)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

> Vue 3 UI preset for [`laranail/license-verifier-ui`](https://opensource.simtabi.com/documentation/laranail/license-verifier-ui/) — the generator writes an owned Vue 3 `LicenseForm` SFC + entry point + JSON endpoints (extending this package) into your app, with a `package.json` for npm/Vite; build with Vite and mount via `mountLicenseVerifier('#lv-license-app')`. Themes: tailwind, bootstrap, unstyled, custom.

Requires PHP `^8.4 || ^8.5` on Laravel `^13` with Vue `^3.4` (npm); pulls `laranail/license-verifier-ui` (the core) automatically.

## Install

```bash
composer require laranail/license-verifier-ui-vue
php artisan laranail::license-verifier-ui.install vue
```

## <a name="documentation"></a>Documentation

Full documentation is at
**[opensource.simtabi.com/documentation/laranail/license-verifier-ui](https://opensource.simtabi.com/documentation/laranail/license-verifier-ui/)** —
see the [Vue preset reference](https://opensource.simtabi.com/documentation/laranail/license-verifier-ui/docs/tools/vue-preset)
for what gets generated and the Vite mounting options.

## License

MIT © Simtabi LLC. See [LICENSE](LICENSE).
