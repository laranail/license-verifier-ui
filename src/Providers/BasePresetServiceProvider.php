<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Providers;

use Illuminate\Support\ServiceProvider;
use Override;
use ReflectionClass;

/**
 * Base service provider for a generated preset package. Registers the package's
 * config at its FLAT key (so config() reads resolve correctly) and its views at
 * the chosen namespace, using paths resolved from the concrete provider's own
 * location. Family bases (Blade/Vue/…) add routes/components in bootPreset().
 */
abstract class BasePresetServiceProvider extends ServiceProvider
{
    /** The view namespace, e.g. "license-verifier-blade". */
    abstract protected function viewNamespace(): string;

    /** The config key / file name, e.g. "license-verifier-blade". */
    abstract protected function configKey(): string;

    #[Override]
    public function register(): void
    {
        $config = $this->packagePath('config/'.$this->configKey().'.php');

        if (is_file($config)) {
            $this->mergeConfigFrom($config, $this->configKey());
        }
    }

    public function boot(): void
    {
        $views = $this->packagePath('resources/views');

        if (is_dir($views)) {
            $this->loadViewsFrom($views, $this->viewNamespace());
        }

        $this->bootPreset();
    }

    /** Hook for family bases (routes, Livewire components, …). */
    protected function bootPreset(): void
    {
        //
    }

    /**
     * Absolute path inside the generated package. The concrete provider lives at
     * `<package>/src/Providers/XServiceProvider.php`, so the root is three levels up.
     */
    protected function packagePath(string $relative = ''): string
    {
        $root = \dirname(new ReflectionClass(static::class)->getFileName(), 3);

        return $relative === '' ? $root : $root.'/'.ltrim($relative, '/');
    }
}
