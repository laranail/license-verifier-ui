<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Validation;

/**
 * Harsh PSR-4 namespace validation for the generated preset package. The
 * generated classes live under a separate path, so the namespace must be
 * specific (≥2 segments) to never shadow the app's own `App\` root.
 */
final class NamespaceValidator
{
    /** @var list<string> PHP reserved words that may not be a namespace segment. */
    private const array RESERVED = [
        'abstract', 'and', 'array', 'as', 'break', 'callable', 'case', 'catch', 'class', 'clone',
        'const', 'continue', 'declare', 'default', 'do', 'echo', 'else', 'elseif', 'empty', 'enddeclare',
        'endfor', 'endforeach', 'endif', 'endswitch', 'endwhile', 'enum', 'eval', 'exit', 'extends', 'final',
        'finally', 'fn', 'for', 'foreach', 'function', 'global', 'goto', 'if', 'implements', 'include',
        'include_once', 'instanceof', 'insteadof', 'interface', 'isset', 'list', 'match', 'namespace', 'new',
        'or', 'print', 'private', 'protected', 'public', 'readonly', 'require', 'require_once', 'return',
        'static', 'switch', 'throw', 'trait', 'try', 'unset', 'use', 'var', 'while', 'xor', 'yield',
        'int', 'float', 'bool', 'string', 'true', 'false', 'null', 'void', 'iterable', 'object', 'mixed', 'never',
    ];

    /**
     * Return a human error message when invalid, or null when valid.
     */
    public function validate(string $namespace): ?string
    {
        $namespace = trim($namespace, "\\ \t\n\r\0\x0B");

        if ($namespace === '') {
            return 'The namespace is required.';
        }

        if (! preg_match('/^[A-Z][A-Za-z0-9]*(\\\\[A-Z][A-Za-z0-9]*)*$/', $namespace)) {
            return 'Use a StudlyCase PSR-4 namespace, e.g. App\\Licensing\\Blade (segments start with a capital letter).';
        }

        $segments = explode('\\', $namespace);

        if (count($segments) < 2) {
            return 'Use at least two segments (e.g. App\\Licensing) so it cannot shadow the app root namespace.';
        }

        foreach ($segments as $segment) {
            if (in_array(strtolower($segment), self::RESERVED, true)) {
                return "\"{$segment}\" is a reserved PHP word and cannot be a namespace segment.";
            }
        }

        return null;
    }

    public function isValid(string $namespace): bool
    {
        return $this->validate($namespace) === null;
    }
}
