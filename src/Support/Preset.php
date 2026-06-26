<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Support;

/**
 * Immutable metadata for a single UI preset. Drives both the root service
 * provider's activation gating and the install command's publish step.
 */
final class Preset
{
    /**
     * @param string $key Stable preset key (blade|filament|livewire|vue)
     * @param string $label Human label shown in the install prompt
     * @param string|null $frameworkClass A class that must exist for the preset
     *                                    to apply (null = framework-free)
     * @param class-string $provider The preset's service provider FQN
     * @param list<string> $publishTags vendor:publish tags this preset exposes
     */
    public function __construct(
        public string $key,
        public string $label,
        public ?string $frameworkClass,
        public string $provider,
        public array $publishTags,
    ) {}

    /**
     * Whether this preset can run in the current app: framework-free presets
     * always can; framework-bound presets need their framework installed.
     */
    public function frameworkAvailable(): bool
    {
        return $this->frameworkClass === null
            || class_exists($this->frameworkClass)
            || interface_exists($this->frameworkClass);
    }
}
