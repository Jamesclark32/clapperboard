@php echo '<?php'.PHP_EOL @endphp

use Illuminate\Support\Facades\Route;

@if($resource->isTeamOwned())
Route::prefix('team/{current_team}')
    ->middleware([
@foreach($resource->middleware as $middleware)
        {!! $resource->middlewareExport($middleware) !!},
@endforeach
    ])
    ->scopeBindings()
    ->group(function () {
@else
Route::middleware([
@foreach($resource->middleware as $middleware)
    {!! $resource->middlewareExport($middleware) !!},
@endforeach
])
    ->group(function () {
@endif
@foreach($resources as $resource)
        Route::{{ $resource->route->httpVerb() }}('{{ $resource->route->url() }}', {{ $resource->controller->qualifiedName() }}::class)->name('{{ $resource->route->name() }}');
@endforeach
});
