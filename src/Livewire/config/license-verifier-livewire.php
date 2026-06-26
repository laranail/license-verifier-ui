<?php

declare(strict_types=1);

return [
    /*
    | Livewire component aliases. Override to rename the registered components.
    */
    'components' => [
        'activation_form' => 'lv-activation-form',
        'status_widget' => 'lv-status-widget',
    ],

    'permission' => env('LICENSE_VERIFIER_LIVEWIRE_PERMISSION'),
];
