<?php

declare(strict_types=1);

use Simtabi\Laranail\Licence\Verifier\Presets\Validation\NamespaceValidator;
use Simtabi\Laranail\Licence\Verifier\Presets\Validation\PathValidator;

it('accepts a valid PSR-4 namespace', function (): void {
    expect((new NamespaceValidator)->isValid('App\\Licensing\\Blade'))->toBeTrue();
});

it('rejects shallow, lowercase, and reserved namespaces', function (): void {
    $v = new NamespaceValidator;

    expect($v->validate('App'))->not->toBeNull()            // single segment
        ->and($v->validate('app\\blade'))->not->toBeNull()  // lowercase
        ->and($v->validate('App\\Class'))->not->toBeNull()  // reserved word
        ->and($v->validate(''))->not->toBeNull();           // empty
});

it('rejects unsafe and protected paths', function (): void {
    $v = new PathValidator(sys_get_temp_dir());

    expect($v->validate('../escape'))->not->toBeNull()
        ->and($v->validate('/absolute'))->not->toBeNull()
        ->and($v->validate('vendor/x'))->not->toBeNull()
        ->and($v->validate('storage/framework/cache'))->not->toBeNull()
        ->and($v->validate(''))->not->toBeNull();
});

it('accepts a clean relative path', function (): void {
    expect(new PathValidator(sys_get_temp_dir())->validate('packages/licensing/blade'))->toBeNull();
});
