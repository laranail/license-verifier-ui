<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Console;

use Simtabi\Laranail\Console\Tools\Commands\Command as BaseCommand;
use Simtabi\Laranail\Console\Tools\Commands\Concerns\SupportsNamespacedNames;

/**
 * Base command for laranail/license-verifier-ui. Extends laranail/console's
 * command base and applies {@see SupportsNamespacedNames} so the
 * `laranail::license-verifier-ui.*` names write past Symfony's validator.
 */
abstract class Command extends BaseCommand
{
    use SupportsNamespacedNames;
}
