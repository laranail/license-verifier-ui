<?php

declare(strict_types=1);

namespace Simtabi\Laranail\Licence\Verifier\Presets\Console;

use Simtabi\Laranail\Licence\Verifier\Presets\Doctor\DefaultThemeValidCheck;
use Simtabi\Laranail\Licence\Verifier\Presets\Doctor\PresetsRegisteredCheck;
use Simtabi\Laranail\Licence\Verifier\Presets\Doctor\VerifierInstalledCheck;
use Simtabi\Laranail\Package\Tools\Commands\Command;
use Simtabi\Laranail\Package\Tools\Services\Doctor\DoctorCheck;
use Simtabi\Laranail\Package\Tools\Services\Doctor\DoctorService;

final class DoctorCommand extends Command
{
    protected $signature = 'laranail::license-verifier-ui.doctor {--json}';

    protected $description = 'Diagnose the license-verifier UI preset generator';

    /**
     * Canonical preset-generator health checks, reused to feed the unified
     * package-tools doctor.
     *
     * @var list<class-string<DoctorCheck>>
     */
    public const array CHECKS = [
        VerifierInstalledCheck::class,
        PresetsRegisteredCheck::class,
        DefaultThemeValidCheck::class,
    ];

    public function handle(): int
    {
        $service = new DoctorService;

        foreach (self::CHECKS as $check) {
            $service->register($check);
        }

        $report = $service->run();
        $summary = $service->summarise($report);
        $failed = $summary['fail'] > 0;

        if ((bool) $this->option('json')) {
            $this->line((string) json_encode([
                'status' => $failed ? 'degraded' : 'ok',
                'summary' => $summary,
                'checks' => array_map(static fn (array $row): array => [
                    'name' => $row['check']->name(),
                    'status' => $row['result']->status->value,
                    'message' => $row['result']->message,
                    'detail' => $row['result']->detail,
                ], $report),
            ], JSON_PRETTY_PRINT));

            return $failed ? self::FAILURE : self::SUCCESS;
        }

        $this->table(['', 'Check', 'Result'], array_map(static fn (array $row): array => [
            $row['result']->status->symbol(),
            $row['check']->name(),
            $row['result']->message,
        ], $report));

        $this->line(sprintf(
            '%d passed, %d warning(s), %d failure(s), %d skipped.',
            $summary['pass'],
            $summary['warn'],
            $summary['fail'],
            $summary['skip'],
        ));

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
