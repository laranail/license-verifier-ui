<?php

declare(strict_types=1);

return [
    'routes' => [
        'enabled' => env('LICENSE_VERIFIER_VUE_ROUTES', true),
        'prefix' => env('LICENSE_VERIFIER_VUE_PREFIX', 'license'),
        'middleware' => ['web'],
    ],

    /*
    | Authorization gate for managing the license (activate/deactivate). Set to a
    | Gate ability name to require it; null relies on the route middleware only.
    */
    'permission' => env('LICENSE_VERIFIER_VUE_PERMISSION'),
];
