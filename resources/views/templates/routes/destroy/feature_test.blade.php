@php echo '<?php'.PHP_EOL @endphp

use App\Models\{{ str($resource->resource)->studly()->singular() }};
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

test('redirects after destroying data', function (): void {
    $user = User::factory()->create();
    ${{ str($resource->resource)->camel()->singular() }} = {{ str($resource->resource)->studly()->singular() }}::factory()->create();

    $response = $this->actingAs($user)->delete(route('{{ $resource->route->name() }}', ['{{ $resource->routeParameterKey() }}' => ${{ str($resource->resource)->camel()->singular() }}]));

    $response->assertRedirect(route('{{ str($resource->resource)->lower()->plural() }}.index'));
})->group('{{ str($resource->resource)->lower() }}');

test('has expected route definition', function (): void {
    $route = Route::getRoutes()->getByName('{{ $resource->route->name() }}');

    expect($route->methods())->toContain('DELETE')
        ->and($route->gatherMiddleware())->toBe(['web'@foreach($resource->middleware as $middleware), {!! $resource->middlewareExport($middleware) !!}@endforeach]);
})->group('{{ str($resource->resource)->lower() }}');

test('redirects guests to the login page', function (): void {
    ${{ str($resource->resource)->camel()->singular() }} = {{ str($resource->resource)->studly()->singular() }}::factory()->create();

    $response = $this->delete(route('{{ $resource->route->name() }}', ['{{ $resource->routeParameterKey() }}' => ${{ str($resource->resource)->camel()->singular() }}]));

    $response->assertRedirect(route('login'));
})->group('{{ str($resource->resource)->lower() }}');

test('uses minimal database queries', function (): void {
    $user = User::factory()->create();
    DB::enableQueryLog();
    ${{ str($resource->resource)->camel()->singular() }} = {{ str($resource->resource)->studly()->singular() }}::factory()->create();

    $response = $this->actingAs($user)->delete(route('{{ $resource->route->name() }}', ['{{ $resource->routeParameterKey() }}' => ${{ str($resource->resource)->camel()->singular() }}]));

    $response->assertRedirect();

    expect(count(DB::getQueryLog()))->toBeLessThanOrEqual(10);
})->group('{{ str($resource->resource)->lower() }}');

test('destroys data', function (): void {
    $user = User::factory()->create();
    ${{ str($resource->resource)->camel()->singular() }} = {{ str($resource->resource)->studly()->singular() }}::factory()->create();

    $response = $this->actingAs($user)->delete(route('{{ $resource->route->name() }}', ['{{ $resource->routeParameterKey() }}' => ${{ str($resource->resource)->camel()->singular() }}]));

    $response->assertRedirect();

    $this->assertDatabaseMissing(${{ str($resource->resource)->camel()->singular() }}->getTable(), [
        'id' => ${{ str($resource->resource)->camel()->singular() }}->id,
    ]);
})->group('{{ str($resource->resource)->lower() }}');
