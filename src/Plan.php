<?php

namespace JamesClark32\Clapperboard;

class Plan
{
    /**
     * @var array<int, PlannedChange>
     */
    private array $changes = [];

    /**
     * @var array<int, string>
     */
    private array $warnings = [];

    public function __construct(
        private readonly string $command,
        private readonly array $context = [],
    ) {}

    public function addChange(PlannedChange $change): void
    {
        $this->changes[] = $change;
    }

    public function addWarning(string $warning): void
    {
        $this->warnings[] = $warning;
    }

    /**
     * @return array<int, PlannedChange>
     */
    public function changes(): array
    {
        return $this->changes;
    }

    /**
     * @return array<int, PlannedChange>
     */
    public function writableChanges(): array
    {
        return array_values(array_filter(
            $this->changes,
            static fn (PlannedChange $change): bool => $change->changed,
        ));
    }

    public function toArray(bool $dryRun): array
    {
        return [
            'status' => 'ok',
            'command' => $this->command,
            'dry_run' => $dryRun,
            'context' => $this->context,
            'summary' => [
                'create' => $this->countOperations('create'),
                'update' => $this->countOperations('update'),
                'noop' => $this->countOperations('noop'),
            ],
            'changes' => array_map(
                static fn (PlannedChange $change): array => $change->toArray(),
                $this->changes,
            ),
            'warnings' => $this->warnings,
        ];
    }

    private function countOperations(string $operation): int
    {
        return count(array_filter(
            $this->changes,
            static fn (PlannedChange $change): bool => $change->operation === $operation,
        ));
    }
}
