<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;

/**
 * Base Filament plugin registering the license page + status widget on a panel.
 * The generated plugin subclasses this and returns its own page/widget classes.
 */
abstract class BaseLicenseVerifierPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'license-verifier';
    }

    /**
     * @return list<class-string>
     */
    abstract protected function pages(): array;

    /**
     * @return list<class-string>
     */
    abstract protected function widgets(): array;

    public function register(Panel $panel): void
    {
        $panel->pages($this->pages())->widgets($this->widgets());
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
