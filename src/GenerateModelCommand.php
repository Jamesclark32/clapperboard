<?php

namespace Clapperbrd\Codegen;

use Clapperbrd\Codegen\Generators\BasePolicyGenerator;
use Clapperbrd\Codegen\Generators\DataDictionaryGenerator;
use Clapperbrd\Codegen\Generators\FactoryGenerator;
use Clapperbrd\Codegen\Generators\InterfaceGenerator;
use Clapperbrd\Codegen\Generators\MigrationGenerator;
use Clapperbrd\Codegen\Generators\ModelGenerator;
use Clapperbrd\Codegen\Generators\NovaResourceGenerator;
use Clapperbrd\Codegen\Generators\PivotMigrationGenerator;
use Clapperbrd\Codegen\Generators\PivotModelGenerator;
use Clapperbrd\Codegen\Generators\PolicyGenerator;
use Clapperbrd\Codegen\Generators\SeederConfigGenerator;
use Clapperbrd\Codegen\Generators\SeederGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateModelCommand extends Command
{
    protected $signature = 'generate:model
{resource : the name of the model}
{description : a description of the model used to populate the data dictionary file. Should describe the model, the data it represents, and how it used in the repository}
{schema : JSON object with "attributes" and "relationships" arrays. See docs for schema details.}
{--dry-run : build a generation plan without writing files}
{--json : output structured JSON}';

    protected $description = 'Generates infrastructure for a new model';

    public function handle(FileWriter $writer): int
    {
        $resource = Str::of($this->argument('resource'))->studly()->singular()->toString();
        $description = Str::of($this->argument('description'))->toString();
        $schema = json_decode($this->argument('schema'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Invalid JSON: '.json_last_error_msg());

            return self::FAILURE;
        }

        $attributes = collect(data_get($schema, 'attributes', []))
            ->map(fn (array $attribute): ModelAttribute => new ModelAttribute($attribute));

        $relationships = collect(data_get($schema, 'relationships', []))
            ->map(fn (array $relationship): ModelRelationship => new ModelRelationship($relationship));

        $softDeletes = (bool) data_get($schema, 'softDeletes', false);
        $modelResource = new ModelResource($resource, $attributes, $relationships, $description, $softDeletes);
        $plan = $this->buildPlan($modelResource);

        if ($this->option('dry-run')) {
            $this->renderPlan($plan, true);

            return self::SUCCESS;
        }

        $writes = $writer->apply($plan, $this->option('json') ? null : $this);

        $this->renderPlan($plan, false, $writes);

        return self::SUCCESS;
    }

    private function buildPlan(ModelResource $modelResource): Plan
    {
        $plan = new Plan('generate:model', [
            'resource' => $modelResource->resource,
            'soft_deletes' => $modelResource->softDeletes,
        ]);

        foreach ($this->getGenerationClasses() as $generatorClass) {
            $generator = new $generatorClass($modelResource);
            $plan->addChange(PlannedChange::fromContents($generator->path(), $generator->fileContents()));
        }

        $modelResource->relationships
            ->filter(fn (ModelRelationship $relationship): bool => in_array($relationship->type, ['belongsToMany', 'morphToMany'], true))
            ->each(function (ModelRelationship $relationship) use ($modelResource, $plan): void {
                $migrationGenerator = new PivotMigrationGenerator($modelResource, $relationship);
                $plan->addChange(PlannedChange::fromContents($migrationGenerator->path(), $migrationGenerator->fileContents()));

                $modelGenerator = new PivotModelGenerator($modelResource, $relationship);
                $plan->addChange(PlannedChange::fromContents($modelGenerator->path(), $modelGenerator->fileContents()));
            });

        return $plan;
    }

    private function getGenerationClasses(): array
    {
        return [
            ModelGenerator::class,
            MigrationGenerator::class,
            FactoryGenerator::class,
            SeederGenerator::class,
            SeederConfigGenerator::class,
            BasePolicyGenerator::class,
            PolicyGenerator::class,
            NovaResourceGenerator::class,
            DataDictionaryGenerator::class,
            InterfaceGenerator::class,
        ];
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

        $this->info('Generation complete. Wrote '.$writes.' file(s).');
    }
}
