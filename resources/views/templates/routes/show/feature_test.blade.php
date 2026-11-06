@php echo '<?php'.PHP_EOL @endphp

use App\Models\{{ str($resource->resource)->studly()->singular() }};
use App\Models\User;
use Illuminate\Support\Facades\Route;

test('returns HTTP 200 response', function (): void {
    $user = User::factory()->create();
    ${{ str($resource->resource)->camel()->singular() }} = {{ str($resource->resource)->studly()->singular() }}::factory()->create({!! $resource->ownedFactoryState() !!});

    $response = $this->actingAs($user)->get(route('{{ $resource->route->name() }}', ['{{ str($resource->resource)->camel()->singular() }}' => ${{ str($resource->resource)->camel()->singular() }}]));

    $response->assertOk();
})->group('{{ str($resource->resource)->lower() }}');

test('has expected route definition', function (): void {
    $route = Route::getRoutes()->getByName('{{ $resource->route->name() }}');

    expect($route->methods())->toContain('GET')
        ->and($route->gatherMiddleware())->toBe(['web'@foreach($resource->middleware as $middleware), {!! $resource->middlewareExport($middleware) !!}@endforeach]);
})->group('{{ str($resource->resource)->lower() }}');

test('redirects guests to the login page', function (): void {
    ${{ str($resource->resource)->camel()->singular() }} = {{ str($resource->resource)->studly()->singular() }}::factory()->create();

    $response = $this->get(route('{{ $resource->route->name() }}', ['{{ str($resource->resource)->camel()->singular() }}' => ${{ str($resource->resource)->camel()->singular() }}]));

    $response->assertRedirect(route('login'));
})->group('{{ str($resource->resource)->lower() }}');

@if($resource->isOwned())
test('forbids access to another user\'s record', function (): void {
    $user = User::factory()->create();
    ${{ str($resource->resource)->camel()->singular() }} = {{ str($resource->resource)->studly()->singular() }}::factory()->create();

    $response = $this->actingAs($user)->get(route('{{ $resource->route->name() }}', ['{{ str($resource->resource)->camel()->singular() }}' => ${{ str($resource->resource)->camel()->singular() }}]));

    $response->assertForbidden();
})->group('{{ str($resource->resource)->lower() }}');
@endif
