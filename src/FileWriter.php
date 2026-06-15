<?php

namespace JamesClark32\Clapperboard;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FileWriter
{
    public function apply(Plan $plan, ?Command $command = null): int
    {
        $writes = 0;

        foreach ($plan->writableChanges() as $change) {
            $absolutePath = $change->absolutePath();

            File::ensureDirectoryExists(dirname($absolutePath));
            File::put($absolutePath, $change->contents);

            if ($command) {
                $command->info(ucfirst($change->operation).' '.$absolutePath);
            }

            $writes++;
        }

        return $writes;
    }
}
