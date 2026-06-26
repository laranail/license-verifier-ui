<?php

declare(strict_types=1);

use Simtabi\Laranail\Licence\Verifier\Presets\Tests\Blade\BladeTestCase;
use Simtabi\Laranail\Licence\Verifier\Presets\Tests\Filament\FilamentTestCase;
use Simtabi\Laranail\Licence\Verifier\Presets\Tests\Livewire\LivewireTestCase;
use Simtabi\Laranail\Licence\Verifier\Presets\Tests\Vue\VueTestCase;

uses(BladeTestCase::class)->in(__DIR__.'/Blade');
uses(FilamentTestCase::class)->in(__DIR__.'/Filament');
uses(LivewireTestCase::class)->in(__DIR__.'/Livewire');
uses(VueTestCase::class)->in(__DIR__.'/Vue');
