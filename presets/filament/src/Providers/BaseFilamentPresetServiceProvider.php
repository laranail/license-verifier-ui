<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Filament\Providers;

use Simtabi\Laranail\Licence\Verifier\Presets\Providers\BasePresetServiceProvider;

/**
 * Family base for a generated Filament preset package. Config + views only; the
 * page/widget are registered on a panel by the generated plugin.
 */
abstract class BaseFilamentPresetServiceProvider extends BasePresetServiceProvider
{
    // Inherits config + view registration from the core base; no routes/components.
}
