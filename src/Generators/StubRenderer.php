<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Generators;

/**
 * Tiny, dependency-free stub renderer. Substitutes `$TOKEN$` placeholders from a
 * token map. The `$UPPER_SNAKE$` delimiter is deliberately chosen so it never
 * collides with Blade `{{ }}`, PHP `$variable`, or JS — letting view stubs carry
 * real Blade alongside generator tokens.
 */
final class StubRenderer
{
    /**
     * @param  array<string, string>  $tokens  token name (without `$` delimiters) => value
     */
    public function render(string $template, array $tokens): string
    {
        $replacements = [];

        foreach ($tokens as $name => $value) {
            $replacements['$'.$name.'$'] = $value;
        }

        return strtr($template, $replacements);
    }

    /**
     * Any leftover `$TOKEN$` placeholders the token map did not cover.
     *
     * @return list<string>
     */
    public function unresolved(string $rendered): array
    {
        if (preg_match_all('/\$[A-Z][A-Z0-9_]*\$/', $rendered, $matches) === false) {
            return [];
        }

        return array_values(array_unique($matches[0]));
    }
}
