# laranail/license-verifier-ui-filament

[![Latest version on Packagist](https://img.shields.io/packagist/v/laranail/license-verifier-ui-filament.svg)](https://packagist.org/packages/laranail/license-verifier-ui-filament)
[![Tests](https://github.com/laranail/license-verifier-ui/actions/workflows/tests.yml/badge.svg)](https://github.com/laranail/license-verifier-ui/actions/workflows/tests.yml)
[![Static analysis](https://github.com/laranail/license-verifier-ui/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/laranail/license-verifier-ui/actions/workflows/static-analysis.yml)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

> Filament UI preset for [`laranail/license-verifier-ui`](https://opensource.simtabi.com/documentation/laranail/license-verifier-ui/) — the generator writes an owned Filament license-management page + status-widget plugin (extending this package); add the generated `LicenseVerifierPlugin::make()` to your panel. Theme: Filament's own design system.

Requires PHP `^8.4 || ^8.5` on Laravel `^13` with `filament/filament ^4.0 || ^3.2`; pulls `laranail/license-verifier-ui` (the core) automatically.

## Install

```bash
composer require laranail/license-verifier-ui-filament
php artisan laranail::license-verifier-ui.install filament
```

## <a name="documentation"></a>Documentation

Full documentation is at
**[opensource.simtabi.com/documentation/laranail/license-verifier-ui](https://opensource.simtabi.com/documentation/laranail/license-verifier-ui/)** —
see the [Filament preset reference](https://opensource.simtabi.com/documentation/laranail/license-verifier-ui/docs/tools/filament-preset)
for what gets generated and the panel wiring.

## License

MIT © Simtabi LLC. See [LICENSE](LICENSE).
