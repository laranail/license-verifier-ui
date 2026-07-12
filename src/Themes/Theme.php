<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Themes;

/**
 * A selectable UI theme (CSS framework / styling variant) for a preset.
 */
final class Theme
{
    public const string TAILWIND = 'tailwind';

    public const string BOOTSTRAP = 'bootstrap';

    public const string ALPINE = 'alpine';

    public const string UNSTYLED = 'unstyled';

    public const string CUSTOM = 'custom';

    public const string FILAMENT = 'filament';

    public function __construct(
        public string $key,
        public string $label,
    ) {}

    /**
     * Labels for the known theme keys.
     *
     * @return array<string, string>
     */
    public static function labels(): array
    {
        return [
            self::TAILWIND => 'Tailwind CSS',
            self::BOOTSTRAP => 'Bootstrap 5',
            self::ALPINE => 'Tailwind + Alpine.js',
            self::UNSTYLED => 'Unstyled (semantic HTML + hooks)',
            self::CUSTOM => 'Custom (themeable shells)',
            self::FILAMENT => 'Filament design system',
        ];
    }

    public static function label(string $key): string
    {
        return self::labels()[$key] ?? ucfirst($key);
    }
}
