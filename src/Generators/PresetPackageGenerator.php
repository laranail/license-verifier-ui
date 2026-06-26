<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Generators;

use Illuminate\Filesystem\Filesystem;
use RuntimeException;
use Simtabi\Laranail\Licence\Verifier\Presets\Presets\PresetDefinition;

/**
 * Renders a preset package from stubs into a destination directory: the preset's
 * generic scaffold (composer.json/README/.gitignore) + its own scaffold + the
 * chosen theme. Pure file generation — composer registration is a separate step.
 */
final readonly class PresetPackageGenerator
{
    public function __construct(
        private StubRenderer $renderer,
        private Filesystem $files,
    ) {}

    /**
     * @return list<string> absolute paths of the files written
     *
     * @throws RuntimeException on a leftover token or a forbidden overwrite
     */
    public function generate(GeneratedPackage $pkg, PresetDefinition $def, string $basePath, bool $force = false): array
    {
        if (! $def->supportsTheme($pkg->theme)) {
            throw new RuntimeException("Preset \"{$def->key}\" does not support the \"{$pkg->theme}\" theme.");
        }

        $tokens = TokenSet::build($pkg, $def);
        $targetRoot = rtrim($basePath, '/').'/'.trim($pkg->path, '/');

        if ($this->files->isDirectory($targetRoot) && ! $force && $this->files->files($targetRoot) !== []) {
            throw new RuntimeException("Target \"{$pkg->path}\" already exists and is not empty (use force to overwrite).");
        }

        $written = [];

        foreach ($this->plan($pkg, $def) as $stubFile => $outputRelative) {
            $template = $this->files->get($stubFile);
            $rendered = $this->renderer->render($template, $tokens);

            $leftover = $this->renderer->unresolved($rendered);
            if ($leftover !== []) {
                throw new RuntimeException(
                    'Unresolved tokens '.implode(', ', $leftover).' in stub '.basename($stubFile)
                );
            }

            $destination = $targetRoot.'/'.$this->renderer->render($outputRelative, $tokens);
            $this->files->ensureDirectoryExists(\dirname($destination));
            $this->files->put($destination, $rendered);
            $written[] = $destination;
        }

        return $written;
    }

    /**
     * Map every source stub to its rendered output path (relative to the package
     * root; tokens still allowed in the output path).
     *
     * @return array<string, string> absolute stub path => relative output path
     */
    private function plan(GeneratedPackage $pkg, PresetDefinition $def): array
    {
        $plan = [];

        // 1. Generic skeleton, authored per preset under its own stubs/scaffold.
        $plan[$def->stubsPath.'/scaffold/composer.json.stub'] = 'composer.json';
        $plan[$def->stubsPath.'/scaffold/README.md.stub'] = 'README.md';
        $plan[$def->stubsPath.'/scaffold/gitignore.stub'] = '.gitignore';

        // 2. Preset-specific scaffold (PHP, routes, config) via the definition's map.
        foreach ($def->fileMap as $stubRelative => $outputRelative) {
            $plan[$def->stubsPath.'/'.$stubRelative] = $outputRelative;
        }

        // 3. The chosen theme: every file under themes/<theme>/ → resources/<rest>.
        $themeRoot = $def->stubsPath.'/themes/'.$pkg->theme;
        if ($this->files->isDirectory($themeRoot)) {
            foreach ($this->files->allFiles($themeRoot) as $file) {
                $relative = ltrim(str_replace($themeRoot, '', $file->getPathname()), '/');
                $relative = preg_replace('/\.stub$/', '', $relative);
                $plan[$file->getPathname()] = 'resources/'.$relative;
            }
        }

        return $plan;
    }
}
