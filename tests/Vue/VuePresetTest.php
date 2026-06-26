<?php

declare(strict_types=1);

it('returns json status with activation fields', function () {
    $this->getJson('license/status')
        ->assertStatus(422)
        ->assertJsonStructure(['data', 'valid', 'fields']);
});

it('activates via the json endpoint using the null driver', function () {
    config()->set('license-verifier.default', 'null');

    $this->postJson('license/activate', ['license_key' => 'DEV'])
        ->assertOk()
        ->assertJsonPath('data.valid', true);
});
