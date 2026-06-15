<?php

namespace JamesClark32\Clapperboard;

use Illuminate\Support\Str;
use JamesClark32\Clapperboard\Resources\Action;
use JamesClark32\Clapperboard\Resources\BrowserTest;
use JamesClark32\Clapperboard\Resources\Component;
use JamesClark32\Clapperboard\Resources\Controller;
use JamesClark32\Clapperboard\Resources\FeatureTest;
use JamesClark32\Clapperboard\Resources\FormRequest;
use JamesClark32\Clapperboard\Resources\Language;
use JamesClark32\Clapperboard\Resources\Query;
use JamesClark32\Clapperboard\Resources\Route;

class Resource
{
    public Action $action;

    public Component $component;

    public Controller $controller;

    public FeatureTest $featureTest;

    public BrowserTest $browserTest;

    public FormRequest $formRequest;

    public Language $language;

    public Query $query;

    public Route $route;

    public string $resource;

    public string $verb;

    public ?string $routeKey;

    public array $middleware;

    public ?array $fillable;

    public function __construct(string $resource, string $verb, ?string $routeKey = null, array $middleware = [])
    {
        $this->resource = $resource;
        $this->verb = $verb;
        $this->routeKey = $routeKey;
        $this->middleware = collect(['auth', 'verified'])
            ->merge($middleware)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $this->route = new Route($resource, $verb, $routeKey);
        $this->controller = new Controller($resource, $verb);
        $this->query = new Query($resource, $verb);
        $this->component = new Component($resource, $verb);
        $this->action = new Action($resource, $verb);
        $this->featureTest = new FeatureTest($resource, $verb);
        $this->browserTest = new BrowserTest($resource, $verb);
        $this->formRequest = new FormRequest($resource, $verb);
        $this->language = new Language($resource, $verb);

        $modelClass = 'App\\Models\\'.Str::of($resource)->studly()->singular();

        if (class_exists($modelClass)) {
            $instance = new $modelClass;
            $this->fillable = collect(['id'])
                ->merge($instance->getFillable())
                ->unique()
                ->values()
                ->all();
        } else {
            $this->fillable = ['id', 'name', 'description'];
        }
    }

    public function isQuery(): bool
    {
        return Verbs::isQuery($this->verb);
    }

    public function isCommand(): bool
    {
        return Verbs::isCommand($this->verb);
    }

    public function templateView(string $template): string
    {
        return 'clapperboard::templates.routes.'.$this->verb.'.'.$template;
    }

    public function writableFillable(): array
    {
        return collect($this->fillable)
            ->reject(fn ($field) => in_array($field, ['id', 'created_at', 'updated_at', 'deleted_at']))
            ->values()
            ->all();
    }

    public function routeParameterKey(): string
    {
        return str($this->resource)->singular()->camel()->toString();
    }

    public function middlewareExport(string $middleware): string
    {
        if (str_contains($middleware, '\\')) {
            return '\\'.$middleware.'::class';
        }

        return "'{$middleware}'";
    }

    public function languageFieldEntries(): array
    {
        return collect($this->fillable)
            ->mapWithKeys(fn ($field) => [$field => str($field)->apa()->toString()])
            ->all();
    }
}
