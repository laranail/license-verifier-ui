<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Console;

use Simtabi\Laranail\Licence\Verifier\Presets\Presets\PresetRegistry;

/**
 * List the installed preset base packages (those whose definition is registered)
 * and the supported themes for each.
 */
final class ListPresetsCommand extends Command
{
    protected $signature = 'laranail::license-verifier-ui.list';

    protected $description = 'List installed license-verifier UI preset packages';

    public function handle(PresetRegistry $registry): int
    {
        if ($registry->all() === []) {
            $this->components->info('No preset packages installed. Try `composer require laranail/license-verifier-ui-blade`.');

            return self::SUCCESS;
        }

        $rows = [];
        foreach ($registry->all() as $def) {
            $rows[] = [$def->key, $def->label, implode(', ', $def->supportedThemes), $def->composerRequire];
        }

        $this->table(['Preset', 'Label', 'Themes', 'Base package'], $rows);

        return self::SUCCESS;
    }
}
