<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Filament\Filament\Pages;

use Filament\Pages\Page;
use Simtabi\Laranail\Licence\Verifier\Drivers\DriverManager;
use Simtabi\Laranail\Licence\Verifier\ValueObjects\LicenseRequest;

class LicensePage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-key';

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected string $view = 'license-verifier-filament::pages.license';

    public function getTitle(): string
    {
        return 'License';
    }

    public ?string $licenseKey = null;

    public ?string $client = null;

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
