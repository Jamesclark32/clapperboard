<?php

namespace Clapperbrd\Codegen;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class RouteFileParser
{
    private string $routeDefinitionPattern = "~Route::(?P<method>\w+)\(\s*'(?P<uri>(?P<resource>[a-z]+)[^']*)'\s*,\s*(?P<controller>[^:]+)::class\s*\)\s*->name\('(?P<name>(?P<resource_name>[a-z]+)\.(?P<verb>index|show|create|store|edit|update|destroy))'\);~m";

    public function parse(string $routeFilePath): Collection
    {
        $routesFileContents = $contents = File::exists($routeFilePath) ? File::get($routeFilePath) : '';

        return $this->extractRouteDefinitions($routesFileContents);
    }

    protected function extractRouteDefinitions(string $routesFileContents): Collection
    {
        preg_match_all($this->routeDefinitionPattern, $routesFileContents, $routeDefinitions, PREG_SET_ORDER);

        $routeDefinitions = array_map(static function ($match) {
            $match = array_filter($match, 'is_string', ARRAY_FILTER_USE_KEY);

            if (preg_match('/\{[^}:]+:(?P<route_key>[^}]+)\}/', data_get($match, 'uri', ''), $routeKeyMatch)) {
                $match['route_key'] = $routeKeyMatch['route_key'];
            }

            return $match;
        }, $routeDefinitions);

        return collect($routeDefinitions);
    }
}
