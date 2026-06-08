<?php

namespace Clapperbrd\Codegen;

use Illuminate\Support\ServiceProvider;

class CodegenServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'clapperbrd-codegen');

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateModelCommand::class,
                GenerateRouteCommand::class,
            ]);
        }
    }
}
