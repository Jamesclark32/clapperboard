@php echo '<?php'.PHP_EOL @endphp

use Illuminate\Support\Facades\Route;

Route::middleware([
@foreach($resource->middleware as $middleware)
    {!! $resource->middlewareExport($middleware) !!},
@endforeach
])
    ->group(function () {
@foreach($resources as $resource)
        Route::{{ $resource->route->httpVerb() }}('{{ $resource->route->url() }}', {{ $resource->controller->qualifiedName() }}::class)->name('{{ $resource->route->name() }}');
@endforeach
});
