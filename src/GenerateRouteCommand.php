<?php

namespace Clapperbrd\Codegen;

use Clapperbrd\Codegen\Generators\ActionGenerator;
use Clapperbrd\Codegen\Generators\BaseCommandGenerator;
use Clapperbrd\Codegen\Generators\BaseQueryCollectionGenerator;
use Clapperbrd\Codegen\Generators\BaseQueryGenerator;
use Clapperbrd\Codegen\Generators\BrowserTestGenerator;
use Clapperbrd\Codegen\Generators\ComponentGenerator;
use Clapperbrd\Codegen\Generators\ComponentTypesGenerator;
use Clapperbrd\Codegen\Generators\ControllerGenerator;
use Clapperbrd\Codegen\Generators\FeatureTestGenerator;
use Clapperbrd\Codegen\Generators\FormRequestGenerator;
use Clapperbrd\Codegen\Generators\LanguageGenerator;
use Clapperbrd\Codegen\Generators\RouteGenerator;
use Clapperbrd\Codegen\Generators\WebRouteGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateRouteCommand extends Command
{
    protected $signature = 'generate:route
{resource : the name of the resource}
{verb : the name of the verb}
{--route-key= : optional route-model binding key for member routes, e.g. slug or ulid}
{--middleware=* : extra route middleware aliases or class names to add after auth and verified}
{--dry-run : build a generation plan without writing files}
{--json : output structured JSON}';

    protected $description = 'Generates infrastructure for a new route';

    private string $resourceName;

    private Verbs $verb;

    public function handle(FileWriter $writer): int
    {
        $this->resourceName = Str::of($this->argument('resource'))->studly()->plural()->toString();
        $verb = Verbs::fromInput($this->argument('verb'));

        if (! $verb) {
            $this->error('Invalid Route Verb "'.$this->argument('verb').'". Expected one of: '.implode(', ', Verbs::values()).', delete');

            return self::FAILURE;
        }

        $this->verb = $verb;

        $resource = new Resource(
            $this->resourceName,
            $this->verb->value,
            $this->option('route-key') ?: null,
            $this->option('middleware') ?: [],
        );

        $plan = $this->buildPlan($resource);

        if ($this->option('dry-run')) {
            $this->renderPlan($plan, true);

            return self::SUCCESS;
        }

        $writes = $writer->apply($plan, $this->option('json') ? null : $this);

        $this->renderPlan($plan, false, $writes);

        return self::SUCCESS;
    }

    private function buildPlan(Resource $resource): Plan
    {
        $plan = new Plan('generate:route', [
            'resource' => $resource->resource,
            'verb' => $resource->verb,
            'route_key' => $resource->routeKey,
            'middleware' => $resource->middleware,
        ]);

        foreach ($this->getGenerationClasses() as $generatorClass) {
            $generator = $this->makeGenerator($generatorClass, $resource);
            $path = $generator->path();

            if (! $path) {
                continue;
            }

            if (method_exists($generator, 'shouldGenerate') && ! $generator->shouldGenerate()) {
                continue;
            }

            $contents = $generator->fileContents();

            if ($contents === null) {
                continue;
            }

            $plan->addChange(PlannedChange::fromContents($path, $contents));
        }

        return $plan;
    }

    private function getGenerationClasses(): array
    {
        return [
            BaseCommandGenerator::class,
            BaseQueryGenerator::class,
            BaseQueryCollectionGenerator::class,
            ControllerGenerator::class,
            ActionGenerator::class,
            FormRequestGenerator::class,
            LanguageGenerator::class,
            ComponentGenerator::class,
            ComponentTypesGenerator::class,
            RouteGenerator::class,
            WebRouteGenerator::class,
            FeatureTestGenerator::class,
            BrowserTestGenerator::class,
        ];
    }

    private function makeGenerator(string $generatorClass, Resource $resource): object
    {
        if (in_array($generatorClass, [
            BaseCommandGenerator::class,
            BaseQueryGenerator::class,
            BaseQueryCollectionGenerator::class,
        ], true)) {
            return new $generatorClass;
        }

        return new $generatorClass($resource);
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
