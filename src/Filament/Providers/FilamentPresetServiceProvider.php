<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Filament\Providers;

use Override;
use Simtabi\Laranail\Package\Tools\Package;
use Simtabi\Laranail\Package\Tools\Providers\PackageServiceProvider;

final class FilamentPresetServiceProvider extends PackageServiceProvider
{
    #[Override]
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laranail/license-verifier-filament-preset')
            ->hasConfigFile('license-verifier-filament')
            ->hasViews('license-verifier-filament');
    }
}
