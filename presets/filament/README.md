# laranail/license-verifier-ui-filament

[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

> Filament UI preset for [`laranail/license-verifier-ui`](https://opensource.simtabi.com/documentation/laranail/license-verifier-ui/).

```bash
composer require laranail/license-verifier-ui-filament
php artisan laranail::license-verifier-ui.install filament
```

Generates an owned Filament license management **page** + status **widget** plugin (extending this
package). Add the generated plugin to your panel:
`$panel->plugin(\<Your\Namespace>\LicenseVerifierPlugin::make())`.

Requires PHP `^8.4 || ^8.5`, Laravel `^13`, `filament/filament ^4.0 || ^3.2`, and
`laranail/license-verifier-ui`. See the [ecosystem docs](https://opensource.simtabi.com/documentation/laranail/license-verifier-ui/docs/).
