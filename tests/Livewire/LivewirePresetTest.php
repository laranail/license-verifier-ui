<?php

declare(strict_types=1);

use Livewire\Livewire;
use Simtabi\Laranail\Licence\Verifier\Presets\Livewire\Livewire\ActivationForm;
use Simtabi\Laranail\Licence\Verifier\Presets\Livewire\Livewire\StatusWidget;

it('renders the activation form', function () {
    Livewire::test(ActivationForm::class)->assertStatus(200);
});

it('activates through the livewire component using the null driver', function () {
    config()->set('license-verifier.default', 'null');

    Livewire::test(ActivationForm::class)
        ->set('licenseKey', 'DEV')
        ->call('activate')
        ->assertSet('activated', true);
});

it('renders the status widget', function () {
    Livewire::test(StatusWidget::class)->assertStatus(200);
});
