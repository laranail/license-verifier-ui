# laranail/license-verifier-ui-blade

[![Tests](https://github.com/laranail/license-verifier-ui/actions/workflows/tests.yml/badge.svg)](https://github.com/laranail/license-verifier-ui/actions/workflows/tests.yml)
[![Static analysis](https://github.com/laranail/license-verifier-ui/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/laranail/license-verifier-ui/actions/workflows/static-analysis.yml)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

`laranail/license-verifier-ui-blade` is not published to Packagist, so there is no registry-version badge to show.

> Blade UI preset for [`laranail/license-verifier-ui`](https://opensource.simtabi.com/documentation/laranail/license-verifier-ui/) — the generator writes a thin, owned Blade license UI into your app, under your own namespace and path, that extends this package. Themes: tailwind, bootstrap, alpine, unstyled, custom.

Requires PHP `^8.4 || ^8.5` on Laravel `^13`; pulls `laranail/license-verifier-ui` (the core) automatically.

## Install

```bash
composer require laranail/license-verifier-ui-blade
php artisan laranail::license-verifier-ui.install blade
```

## <a name="documentation"></a>Documentation

Full documentation is at
**[opensource.simtabi.com/documentation/laranail/license-verifier-ui](https://opensource.simtabi.com/documentation/laranail/license-verifier-ui/)** —
see the [Blade preset reference](https://opensource.simtabi.com/documentation/laranail/license-verifier-ui/docs/tools/blade-preset)
for what gets generated and how to render the form.

## License

MIT © Simtabi LLC. See [LICENSE](LICENSE).
