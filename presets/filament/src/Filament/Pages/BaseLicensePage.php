<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Filament\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Override;
use Simtabi\Laranail\Licence\Verifier\Drivers\DriverManager;
use Simtabi\Laranail\Licence\Verifier\ValueObjects\LicenseRequest;

/**
 * Base Filament license management page. The generated page subclasses this and
 * sets its `$view`.
 */
abstract class BaseLicensePage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-key';

    public ?string $licenseKey = null;

    public ?string $client = null;

    #[Override]
    public function getTitle(): string
    {
        return __('license-verifier::license-verifier.license');
    }

    /**
     * @return array<string, mixed>
     */
    public function getLicenseInfo(): array
    {
        return app(DriverManager::class)->active()->getLicenseInfo()->toArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getActivationFields(): array
    {
        return app(DriverManager::class)->active()->activationFields();
    }

    public function activate(): void
    {
        $result = app(DriverManager::class)->active()->activate(new LicenseRequest(
            key: (string) $this->licenseKey,
            client: $this->client,
        ));

        $this->dispatch('license-updated', valid: $result->isUsable());
    }

    public function deactivate(): void
    {
        app(DriverManager::class)->active()->deactivate();
        $this->dispatch('license-updated', valid: false);
    }
}
