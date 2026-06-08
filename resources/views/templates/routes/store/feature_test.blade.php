@php echo '<?php'.PHP_EOL @endphp

use App\Models\{{ str($resource->resource)->studly()->singular() }};
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

test('redirects after storing data', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('{{ $resource->route->name() }}'), [
@foreach($resource->writableFillable() as $field)
        '{{ $field }}' => 'Test {{ str($field)->apa() }}',
@endforeach
    ]);

    $response->assertRedirect();
})->group('{{ str($resource->resource)->lower() }}');

test('has expected route definition', function (): void {
    $route = Route::getRoutes()->getByName('{{ $resource->route->name() }}');

    expect($route->methods())->toContain('POST')
        ->and($route->gatherMiddleware())->toBe(['web'@foreach($resource->middleware as $middleware), {!! $resource->middlewareExport($middleware) !!}@endforeach]);
})->group('{{ str($resource->resource)->lower() }}');

test('redirects guests to the login page', function (): void {
    $response = $this->post(route('{{ $resource->route->name() }}'), [
@foreach($resource->writableFillable() as $field)
        '{{ $field }}' => 'Test {{ str($field)->apa() }}',
@endforeach
    ]);

    $response->assertRedirect(route('login'));
})->group('{{ str($resource->resource)->lower() }}');

test('uses minimal database queries', function (): void {
    $user = User::factory()->create();
    DB::enableQueryLog();

    $response = $this->actingAs($user)->post(route('{{ $resource->route->name() }}'), [
@foreach($resource->writableFillable() as $field)
        '{{ $field }}' => 'Test {{ str($field)->apa() }}',
@endforeach
    ]);

    $response->assertRedirect();

    expect(count(DB::getQueryLog()))->toBeLessThanOrEqual(10);
})->group('{{ str($resource->resource)->lower() }}');

test('stores data', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('{{ $resource->route->name() }}'), [
@foreach($resource->writableFillable() as $field)
        '{{ $field }}' => 'Stored {{ str($field)->apa() }}',
@endforeach
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas((new {{ str($resource->resource)->studly()->singular() }})->getTable(), [
@foreach($resource->writableFillable() as $field)
        '{{ $field }}' => 'Stored {{ str($field)->apa() }}',
@endforeach
    ]);
})->group('{{ str($resource->resource)->lower() }}');

@if(in_array('name', $resource->writableFillable()))
test('validates name is required', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('{{ $resource->route->name() }}'), []);

    $response->assertSessionHasErrors('name');
})->group('{{ str($resource->resource)->lower() }}');
@endif
