<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Livewire\Providers;

use Livewire\Livewire;
use Override;
use Simtabi\Laranail\Licence\Verifier\Presets\Livewire\Livewire\ActivationForm;
use Simtabi\Laranail\Licence\Verifier\Presets\Livewire\Livewire\StatusWidget;
use Simtabi\Laranail\Package\Tools\Package;
use Simtabi\Laranail\Package\Tools\Providers\PackageServiceProvider;

final class LivewirePresetServiceProvider extends PackageServiceProvider
{
    #[Override]
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laranail/license-verifier-livewire-preset')
            ->hasConfigFile('license-verifier-livewire')
            ->hasViews('license-verifier-livewire');
    }

    #[Override]
    public function packageBooted(): void
    {
        if (! class_exists(Livewire::class)) {
            return;
        }

        Livewire::component(
            (string) config('license-verifier-livewire.components.activation_form', 'lv-activation-form'),
            ActivationForm::class,
        );

        Livewire::component(
            (string) config('license-verifier-livewire.components.status_widget', 'lv-status-widget'),
            StatusWidget::class,
        );
    }
}
