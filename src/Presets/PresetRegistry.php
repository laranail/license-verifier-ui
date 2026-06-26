<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Presets;

/**
 * Runtime registry of installed presets. The core binds this as a singleton;
 * each installed preset package contributes its {@see PresetDefinition} from its
 * own service provider, so the install command only ever offers presets whose
 * package is actually present.
 */
final class PresetRegistry
{
    /** @var array<string, PresetDefinition> */
    private array $presets = [];

    public function register(PresetDefinition $definition): void
    {
        $this->presets[$definition->key] = $definition;
    }

    /**
     * @return array<string, PresetDefinition>
     */
    public function all(): array
    {
        return $this->presets;
    }

    public function get(string $key): ?PresetDefinition
    {
        return $this->presets[$key] ?? null;
    }

    public function has(string $key): bool
    {
        return isset($this->presets[$key]);
    }

    /**
     * @return list<string>
     */
    public function keys(): array
    {
        return array_keys($this->presets);
    }
}
