<?php

namespace JamesClark32\Clapperboard;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    private const ManagedBlockStart = '<!-- clapperboard:start -->';

    private const ManagedBlockEnd = '<!-- clapperboard:end -->';

    protected $signature = 'clapperboard:install
{--dry-run : Build an installation plan without writing files}
{--json : Output structured JSON}';

    protected $description = 'Installs agent-facing instructions for Clapperboard';

    public function handle(FileWriter $writer): int
    {
        $plan = $this->buildPlan();

        if ($this->option('dry-run')) {
            $this->renderPlan($plan, true);

            return self::SUCCESS;
        }

        $writes = $writer->apply($plan, $this->option('json') ? null : $this);

        $this->renderPlan($plan, false, $writes);

        return self::SUCCESS;
    }

    private function buildPlan(): Plan
    {
        $plan = new Plan('clapperboard:install', [
            'targets' => [
                'AGENTS.md',
                '.github/copilot-instructions.md',
            ],
        ]);

        foreach (['AGENTS.md', '.github/copilot-instructions.md'] as $path) {
            $plan->addChange(PlannedChange::fromContents($path, $this->desiredContents($path)));
        }

        return $plan;
    }

    private function desiredContents(string $path): string
    {
        $absolutePath = base_path($path);
        $managedBlock = $this->managedBlock();

        if (! File::exists($absolutePath)) {
            return $this->newFileContents($path, $managedBlock);
        }

        $existingContents = File::get($absolutePath);

        if (str_contains($existingContents, self::ManagedBlockStart) && str_contains($existingContents, self::ManagedBlockEnd)) {
            return (string) preg_replace(
                '/'.preg_quote(self::ManagedBlockStart, '/').'.*?'.preg_quote(self::ManagedBlockEnd, '/').'/s',
                $managedBlock,
                $existingContents,
            );
        }

        return rtrim($existingContents).PHP_EOL.PHP_EOL.$managedBlock.PHP_EOL;
    }

    private function newFileContents(string $path, string $managedBlock): string
    {
        if ($path === 'AGENTS.md') {
            return "# Agent Instructions\n\n{$managedBlock}\n";
        }

        return $managedBlock.PHP_EOL;
    }

    private function managedBlock(): string
    {
        return implode(PHP_EOL, [
            self::ManagedBlockStart,
            '## Clapperboard',
            '',
            'This project uses `jamesclark32/clapperboard` for scaffolding and code creation.',
            '',
            'Agents must prefer Clapperboard over manual scaffolding whenever Clapperboard can satisfy the request.',
            '',
            'Required workflow:',
            '1. Run the relevant Clapperboard command with `--dry-run --json` first.',
            '2. Review the planned file changes.',
            '3. Re-run the command without `--dry-run` to apply generation.',
            '',
            'Prefer Clapperboard for:',
            '- new models',
            '- migrations, factories, seeders, policies, and docs',
            '- resource routes',
            '- controllers, actions, queries, form requests, pages, tests, and language files',
            '',
            'Only hand-write scaffolding when Clapperboard does not support the task.',
            '',
            'Primary commands:',
            '- `php artisan generate:model ... --dry-run --json`',
            '- `php artisan generate:route ... --dry-run --json`',
            self::ManagedBlockEnd,
        ]);
    }

    private function renderPlan(Plan $plan, bool $dryRun, ?int $writes = null): void
    {
        if ($this->option('json')) {
            $payload = $plan->toArray($dryRun);

            if ($writes !== null) {
                $payload['writes'] = $writes;
            }

            $this->line(json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            return;
        }

        foreach ($plan->changes() as $change) {
            $this->line(sprintf('[%s] %s', $change->operation, $change->path));
        }

        if ($dryRun) {
            $this->info('Dry run complete. No files were written.');

            return;
        }

        $this->info('Clapperboard instructions installed. Wrote '.$writes.' file(s).');
    }
}
