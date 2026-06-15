<?php

namespace JamesClark32\Clapperboard;

use Illuminate\Support\ServiceProvider;

class CodegenServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'clapperboard');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                GenerateModelCommand::class,
                GenerateRouteCommand::class,
            ]);
        }
    }
}
