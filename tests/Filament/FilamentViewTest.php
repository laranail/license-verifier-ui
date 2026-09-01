<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/**
 * Compile the generated Filament page + widget Blade views with Filament's
 * Blade components registered. Compilation resolves every `<x-filament…>` tag
 * and parses all directives, so a broken view stub fails here.
 */
it('compiles the generated Filament page view with its components resolved', function (): void {
    $compiled = Blade::compileString(file_get_contents($this->generatedViewsPath.'/pages/license.blade.php'));

    expect($compiled)
        ->toContain('<?php')
        ->toContain('license-verifier::license-verifier.activate');
});

it('compiles the generated Filament widget view with its components resolved', function (): void {
    $compiled = Blade::compileString(file_get_contents($this->generatedViewsPath.'/widgets/status.blade.php'));

    expect($compiled)->toContain('<?php');
});
