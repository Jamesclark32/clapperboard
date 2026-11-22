<?php

namespace JamesClark32\Clapperboard;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class FileWriter
{
    public function apply(Plan $plan, ?Command $command = null): int
    {
        $written = [];

        foreach ($plan->writableChanges() as $change) {
            $absolutePath = $change->absolutePath();

            File::ensureDirectoryExists(dirname($absolutePath));
            File::put($absolutePath, $change->contents);

            if ($command) {
                $command->info(ucfirst($change->operation).' '.$absolutePath);
            }

            $written[] = $absolutePath;
        }

        $this->format($written);

        return count($written);
    }

    /**
     * Format written files with the host app's own formatters, when installed, so
     * generated code passes the app's style checks.
     */
    private function format(array $paths): void
    {
        $php = array_values(array_filter($paths, fn (string $path): bool => str_ends_with($path, '.php')));
        $frontend = array_values(array_filter($paths, fn (string $path): bool => (bool) preg_match('/\.(vue|ts|js|md)$/', $path)));

        if ($php !== [] && File::exists(base_path('vendor/bin/pint'))) {
            $this->run([PHP_BINARY, base_path('vendor/bin/pint'), ...$php]);
        }

        if ($frontend === []) {
            return;
        }

        if (File::exists(base_path('node_modules/.bin/vp'))) {
            $this->run([base_path('node_modules/.bin/vp'), 'fmt', ...$frontend]);
        } elseif (File::exists(base_path('node_modules/.bin/prettier'))) {
            $this->run([base_path('node_modules/.bin/prettier'), '--write', ...$frontend]);
        }
    }

    private function run(array $command): void
    {
        // Formatting is best effort: a formatter failure leaves the files unformatted.
        (new Process($command, base_path()))->setTimeout(120)->run();
    }
}
