<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Preset activation
    |--------------------------------------------------------------------------
    |
    | Each UI preset auto-activates when its underlying framework is installed
    | (Blade and Vue are framework-free, so they are always available). Use the
    | `enabled` flag to override that per preset:
    |
    |   null  => auto: active only when the framework is present (default)
    |   false => hard-disabled, even if the framework is installed
    |   true  => force-enabled (still requires the framework to be present)
    |
    | `publishable` controls whether the preset is offered by the
    | `laranail::license-verifier-ui.install` command.
    |
    */

    'presets' => [

        'blade' => [
            'enabled' => env('LICENSE_VERIFIER_PRESET_BLADE'),
            'publishable' => true,
        ],

        'filament' => [
            'enabled' => env('LICENSE_VERIFIER_PRESET_FILAMENT'),
            'publishable' => true,
        ],

        'livewire' => [
            'enabled' => env('LICENSE_VERIFIER_PRESET_LIVEWIRE'),
            'publishable' => true,
        ],

        'vue' => [
            'enabled' => env('LICENSE_VERIFIER_PRESET_VUE'),
            'publishable' => true,
        ],

    ],

];
