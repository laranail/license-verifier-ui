<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Console;

use Simtabi\Laranail\Licence\Verifier\Presets\Doctor\Checks;
use Simtabi\Laranail\Package\Tools\Services\Doctor\DoctorReporter;

final class DoctorCommand extends Command
{
    protected $signature = 'laranail::license-verifier-ui.doctor {--json}';

    protected $description = 'Diagnose the license-verifier UI preset generator';

    public function handle(): int
    {
        return DoctorReporter::render($this, Checks::all(), (bool) $this->option('json'));
    }
}
