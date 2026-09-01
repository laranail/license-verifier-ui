<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Generators;

use Simtabi\Laranail\Licence\Verifier\Presets\Presets\PresetDefinition;

/**
 * Builds the `$TOKEN$` => value map the {@see StubRenderer} consumes, from a
 * {@see GeneratedPackage} and its {@see PresetDefinition}. Token names are
 * UPPER_SNAKE (rendered as `$NAME$`).
 */
final class TokenSet
{
    /** The core package namespace that generated subclasses extend. */
    public const string BASE_NAMESPACE = 'Simtabi\\Laranail\\Licence\\Verifier\\Presets';

    /**
     * @return array<string, string>
     */
    public static function build(GeneratedPackage $pkg, PresetDefinition $def): array
    {
        return [
            'PHP_NAMESPACE' => $pkg->namespace,
            'PHP_NAMESPACE_JSON' => str_replace('\\', '\\\\', $pkg->namespace),
            'BASE_NAMESPACE' => self::BASE_NAMESPACE,
            'VENDOR_KEBAB' => $pkg->vendorKebab(),
            'PACKAGE_KEBAB' => $pkg->packageKebab(),
            'COMPOSER_NAME' => $pkg->composerName(),
            'BASE_PACKAGE' => $pkg->basePackage,
            'BASE_CONSTRAINT' => $pkg->baseConstraint,
            'VIEW_NAMESPACE' => $pkg->viewNamespace(),
            'CONFIG_KEY' => $pkg->configKey(),
            'ASSET_PATH' => $pkg->assetPath(),
            'ROUTE_NAME_PREFIX' => $pkg->routeNamePrefix(),
            'PRESET_LABEL' => $def->label,
            'THEME' => $pkg->theme,
            'PROVIDER_CLASS' => $pkg->providerClass(),
            'PROVIDER_FQCN' => $pkg->providerFqcn(),
            'PROVIDER_FQCN_JSON' => str_replace('\\', '\\\\', $pkg->providerFqcn()),
        ];
    }
}
