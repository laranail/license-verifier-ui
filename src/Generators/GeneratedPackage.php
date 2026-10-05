<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Generators;

use Illuminate\Support\Str;

/**
 * Value object describing the package the generator will write: the user's
 * choices (preset, theme, namespace, path, composer name) plus the derived
 * resource identifiers (view namespace, config key, asset path, route prefix).
 */
final readonly class GeneratedPackage
{
    public function __construct(
        public string $presetKey,
        public string $theme,
        public string $namespace,
        public string $path,
        public string $vendor,
        public string $package,
        public string $basePackage,
        public string $baseConstraint = '^1.0',
        public ?string $frameworkRequire = null,
    ) {}

    public function composerName(): string
    {
        return Str::kebab($this->vendor) . '/' . Str::kebab($this->package);
    }

    public function vendorKebab(): string
    {
        return Str::kebab($this->vendor);
    }

    public function packageKebab(): string
    {
        return Str::kebab($this->package);
    }

    public function providerClass(): string
    {
        return Str::studly($this->presetKey) . 'PresetServiceProvider';
    }

    /** The namespace the generated provider lives in (always under Providers). */
    public function providerNamespace(): string
    {
        return $this->namespace . '\\Providers';
    }

    /** Fully-qualified class name of the generated provider. */
    public function providerFqcn(): string
    {
        return $this->providerNamespace() . '\\' . $this->providerClass();
    }

    public function viewNamespace(): string
    {
        return 'laranail-license-verifier-' . $this->presetKey;
    }

    public function configKey(): string
    {
        return 'license-verifier-' . $this->presetKey;
    }

    public function assetPath(): string
    {
        return 'vendor/license-verifier-' . $this->presetKey;
    }

    /**
     * Route-name prefix baked into a newly generated package's config and views.
     *
     * Vendor-scoped, because route names share one flat registry with the host
     * and every other package. Packages generated before this changed carry
     * `license-verifier.` / `license-verifier-vue.` in their own config and
     * views, and keep them: the base providers read the prefix from that config,
     * so nothing about an existing package changes. See {@see self::legacyRouteNamePrefix()}.
     */
    public function routeNamePrefix(): string
    {
        return $this->presetKey === 'vue'
            ? 'laranail-license-verifier-ui-vue.'
            : 'laranail-license-verifier-ui.';
    }

    /**
     * The prefix this generator wrote before it was vendor-scoped. Still the
     * base providers' fallback when a package's config has no `routes.name`.
     */
    public function legacyRouteNamePrefix(): string
    {
        return $this->presetKey === 'vue' ? 'license-verifier-vue.' : 'license-verifier.';
    }
}
