<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Validation;

use RuntimeException;
use Simtabi\Laranail\Package\Tools\Support\PathResolver;

/**
 * Harsh validation for the user-chosen output path. The path must be a
 * relative location that resolves inside the application root, whose parent is
 * writable, and which is not a protected directory. Existing non-empty targets
 * are rejected unless overwriting is forced.
 */
final readonly class PathValidator
{
    /** @var list<string> */
    private const array PROTECTED_PREFIXES = ['vendor', 'node_modules', 'storage/framework', '.git'];

    public function __construct(private string $basePath) {}

    /**
     * Return a human error message when invalid, or null when valid.
     */
    public function validate(string $path, bool $force = false): ?string
    {
        $path = trim($path);

        if ($path === '') {
            return 'The output path is required.';
        }

        try {
            PathResolver::validatePathSecurity($path);
        } catch (RuntimeException $e) {
            return $e->getMessage();
        }

        if (PathResolver::isAbsolutePath($path)) {
            return 'Use a relative path inside your application (e.g. packages/licensing/blade).';
        }

        $normalized = str_replace('\\', '/', trim($path, '/'));

        foreach (self::PROTECTED_PREFIXES as $protected) {
            if ($normalized === $protected || str_starts_with($normalized.'/', $protected.'/')) {
                return "Refusing to write into the protected directory \"{$protected}\".";
            }
        }

        $absolute = PathResolver::joinPaths($this->basePath, $normalized);

        // Must stay inside the application root.
        if (! str_starts_with($absolute, PathResolver::normalizePath($this->basePath))) {
            return 'The path must resolve inside the application root.';
        }

        if (is_dir($absolute) && ! $force && $this->isNonEmptyDir($absolute)) {
            return "\"{$path}\" already exists and is not empty. Re-run with --force to overwrite.";
        }

        $parent = \dirname($absolute);
        $existingAncestor = $parent;
        while (! is_dir($existingAncestor) && \dirname($existingAncestor) !== $existingAncestor) {
            $existingAncestor = \dirname($existingAncestor);
        }

        if (! is_writable($existingAncestor)) {
            return "The directory \"{$path}\" cannot be created (parent is not writable).";
        }

        return null;
    }

    public function isValid(string $path, bool $force = false): bool
    {
        return $this->validate($path, $force) === null;
    }

    private function isNonEmptyDir(string $dir): bool
    {
        $entries = @scandir($dir);

        return $entries !== false && count($entries) > 2; // more than '.' and '..'
    }
}
