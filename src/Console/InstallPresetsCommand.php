<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Console;

use Simtabi\Laranail\Licence\Verifier\Presets\Support\PresetRegistry;
use Simtabi\Laranail\Package\Tools\Commands\Command;

use function Laravel\Prompts\multiselect;

/**
 * Interactive installer: pick one or more presets and publish their config,
 * views and assets. Uses the `laranail::` namespaced command name via the
 * package-tools Command base (which carries SupportsNamespacedNames).
 */
final class InstallPresetsCommand extends Command
{
    protected $signature = 'laranail::license-verifier-ui.install';

    protected $description = 'Install one or more license-verifier UI presets';

    public function handle(): int
    {
        $installable = PresetRegistry::installable();

        if ($installable === []) {
            $this->components->warn(
                'No installable presets found. The Blade and Vue presets are always available; '
                .'install filament/filament or livewire/livewire to enable those.'
            );

            return self::FAILURE;
        }

        $options = [];
        foreach ($installable as $preset) {
            $options[$preset->key] = $preset->label;
        }

        /** @var list<string> $chosen */
        $chosen = multiselect(
            label: 'Which preset(s) do you want to install?',
            options: $options,
            hint: "Publishes each selected preset's config, views and assets.",
        );

        if ($chosen === []) {
            $this->components->info('No presets selected. Nothing to publish.');

            return self::SUCCESS;
        }

        foreach ($chosen as $key) {
            $preset = PresetRegistry::get($key);
            if ($preset === null) {
                continue;
            }

            foreach ($preset->publishTags as $tag) {
                $this->call('vendor:publish', ['--tag' => $tag]);
            }

            $this->components->info(sprintf(
                'Published %s. It auto-activates when its framework is present; '
                .'set LICENSE_VERIFIER_PRESET_%s=false to disable it.',
                $preset->label,
                strtoupper($key),
            ));
        }

        return self::SUCCESS;
    }
}
