<?php

namespace Clapperbrd\Codegen\Generators;

use Clapperbrd\Codegen\Resource;
use Clapperbrd\Codegen\RouteFileParser;
use Clapperbrd\Codegen\Verbs;
use Illuminate\Support\Collection;

class RouteGenerator
{
    private Resource $resource;

    private RouteFileParser $routeFileParser;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
        $this->routeFileParser = new RouteFileParser;
    }

    public function path(): string
    {
        return $this->resource->route->qualifiedFilename();
    }

    public function fileContents(): string
    {
        $resources = $this->parseExistingRoutes();

        $resources->push($this->resource);

        $resources = $this->sortResources($resources);

        return view($this->resource->templateView('routes'), [
            'resource' => $this->resource,
            'resources' => $resources,
        ])->render();
    }

    private function parseExistingRoutes(): Collection
    {
        $routes = $this->routeFileParser->parse($this->resource->route->qualifiedFilename());

        return $routes->transform(function ($route) {
            return new Resource(data_get($route, 'resource'), data_get($route, 'verb'), data_get($route, 'route_key'));
        });
    }

    private function sortResources(Collection $resources): Collection
    {
        return $resources->sort(function ($one, $two) {

            $onePriority = Verbs::tryFrom($one->verb)?->sortOrder();
            $twoPriority = Verbs::tryFrom($two->verb)?->sortOrder();

            return $onePriority <=> $twoPriority;
        })
            ->unique('verb');
    }
}
