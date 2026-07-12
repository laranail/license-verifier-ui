<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Generator defaults
    |--------------------------------------------------------------------------
    |
    | Defaults the `laranail::license-verifier-ui.install` command falls back to:
    | `default_theme` and `composer_vendor` seed the prompts, while `symlink` and
    | `composer_timeout` control how the generated package is registered via composer.
    |
    */

    'default_theme' => env('LICENSE_VERIFIER_UI_THEME', 'tailwind'),

    // Seeds the vendor half of the "vendor/package" prompt. The PHP namespace is
    // derived from the chosen vendor/package (or entered explicitly) at install time.
    'composer_vendor' => env('LICENSE_VERIFIER_UI_VENDOR'),

    'symlink' => env('LICENSE_VERIFIER_UI_SYMLINK', true),

    'composer_timeout' => env('LICENSE_VERIFIER_UI_COMPOSER_TIMEOUT', 300),

];
