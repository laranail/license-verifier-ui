<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Filament\Filament\Pages;

use Override;
use BackedEnum;
use Filament\Pages\Page;
use Simtabi\Laranail\Licence\Verifier\Drivers\DriverManager;
use Simtabi\Laranail\Licence\Verifier\ValueObjects\LicenseRequest;

/**
 * Base Filament license management page. The generated page subclasses this and
 * sets its `$view`.
 */
abstract class BaseLicensePage extends Page
{
    /** Browser event dispatched after the licence is activated or deactivated. */
    public const string LICENSE_UPDATED_EVENT = 'laranail-license-verifier-ui:license-updated';

    /**
     * @deprecated Since 0.1 (2026-10). The bare `license-updated` browser event, still dispatched beside
     *             {@see self::LICENSE_UPDATED_EVENT} so existing listeners keep working. Removed no
     *             earlier than the next minor after 0.1.
     */
    public const string LEGACY_LICENSE_UPDATED_EVENT = 'license-updated';

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

        $this->dispatchLicenseUpdated($result->isUsable());
    }

    public function deactivate(): void
    {
        app(DriverManager::class)->active()->deactivate();
        $this->dispatchLicenseUpdated(false);
    }

    private function dispatchLicenseUpdated(bool $valid): void
    {
        $this->dispatch(self::LICENSE_UPDATED_EVENT, valid: $valid);
        $this->dispatch(self::LEGACY_LICENSE_UPDATED_EVENT, valid: $valid); // @phpstan-ignore classConstant.deprecated
    }
}
