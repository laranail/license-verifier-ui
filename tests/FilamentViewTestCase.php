<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Tests;

use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Override;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;
use Simtabi\Laranail\Licence\Verifier\Presets\Generators\GeneratedPackage;
use Simtabi\Laranail\Licence\Verifier\Presets\Generators\PresetPackageGenerator;
use Simtabi\Laranail\Licence\Verifier\Presets\Presets\PresetRegistry;

/**
 * TestCase variant that boots Filament's Blade-component providers so the
 * generated Filament views (which use `<x-filament-panels::page>`,
 * `<x-filament::section>`, …) can be compiled. Scoped to the Filament suite.
 *
 * Full interactive Livewire rendering of Filament pages needs Filament's own
 * panel HTTP bootstrap (its workbench harness); compiling the views catches the
 * realistic failure mode — broken Blade or unresolved component tags in the stubs.
 */
abstract class FilamentViewTestCase extends TestCase
{
    /** Rendered views directory of a freshly generated Filament package. */
    protected string $generatedViewsPath;

    #[Override]
    protected function getPackageProviders($app): array
    {
        return [
            ...parent::getPackageProviders($app),
            BladeCaptureDirectiveServiceProvider::class,
            SupportServiceProvider::class,
            ActionsServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            NotificationsServiceProvider::class,
            SchemasServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
            FilamentServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $def = app(PresetRegistry::class)->get('filament');
        $tmp = sys_get_temp_dir().'/lvui-filament-views-'.uniqid();
        mkdir($tmp, 0777, true);

        $pkg = new GeneratedPackage(
            presetKey: 'filament',
            theme: 'filament',
            namespace: 'Acme\\FilamentViews',
            path: 'pkg',
            vendor: 'acme',
            package: 'license-verifier-filament',
            basePackage: $def->composerRequire,
            frameworkRequire: $def->frameworkRequire,
        );

        app(PresetPackageGenerator::class)->generate($pkg, $def, $tmp, force: true);

        $this->generatedViewsPath = $tmp.'/pkg/resources/views';
    }
}
