<?php

declare(strict_types=1);

it('renders the unlicensed page with the driver-aware form', function () {
    $this->get('license/unlicensed')
        ->assertOk()
        ->assertSee('name="license_key"', false);
});

it('reports an invalid status when unactivated', function () {
    $this->getJson('license/status')
        ->assertStatus(422)
        ->assertJsonPath('valid', false);
});

it('activates through the controller using the null driver', function () {
    config()->set('license-verifier.default', 'null');

    $this->postJson('license/activate', ['license_key' => 'DEV-KEY'])
        ->assertOk()
        ->assertJsonPath('data.valid', true);
});

it('deactivates through the controller', function () {
    config()->set('license-verifier.default', 'null');

    $this->postJson('license/deactivate')
        ->assertOk()
        ->assertJsonPath('data.deactivated', true);
});

it('skips the reminder through the controller', function () {
    $this->postJson('license/reminder/skip', ['days' => 2])->assertOk();
});

it('blocks deactivation when a permission gate is configured and unmet', function () {
    config()->set('license-verifier.default', 'null');
    config()->set('license-verifier-blade.permission', 'manage-license');

    // No authenticated user with the ability → forbidden.
    $this->postJson('license/deactivate')->assertStatus(403);
});
