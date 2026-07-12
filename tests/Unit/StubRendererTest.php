<?php

declare(strict_types=1);

use Simtabi\Laranail\Licence\Verifier\Presets\Generators\StubRenderer;

it('substitutes $TOKEN$ placeholders', function (): void {
    $renderer = new StubRenderer;

    expect($renderer->render('namespace $PHP_NAMESPACE$;', ['PHP_NAMESPACE' => 'App\\X']))
        ->toBe('namespace App\\X;');
});

it('leaves Blade braces untouched and flags leftover tokens', function (): void {
    $renderer = new StubRenderer;

    $out = $renderer->render('{{ $field }} hello $MISSING$', ['PHP_NAMESPACE' => 'x']);

    expect($out)->toContain('{{ $field }}')
        ->and($renderer->unresolved($out))->toBe(['$MISSING$']);
});

it('reports no leftovers when fully resolved', function (): void {
    $renderer = new StubRenderer;

    expect($renderer->unresolved($renderer->render('$A$ and $B$', ['A' => '1', 'B' => '2'])))->toBe([]);
});
