@php echo '<?php'.PHP_EOL @endphp

use App\Models\User;
use Illuminate\Support\Facades\Route;

test('returns HTTP 200 response', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('{{ $resource->route->name() }}'));

    $response->assertOk();
})->group('{{ str($resource->resource)->lower() }}');

test('has expected route definition', function (): void {
    $route = Route::getRoutes()->getByName('{{ $resource->route->name() }}');

    expect($route->methods())->toContain('GET')
        ->and($route->gatherMiddleware())->toBe(['web'@foreach($resource->middleware as $middleware), {!! $resource->middlewareExport($middleware) !!}@endforeach]);
})->group('{{ str($resource->resource)->lower() }}');

test('returns expected inertia component and props', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('{{ $resource->route->name() }}'));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('{{ $resource->component->namespacedName() }}')
            ->where('pageTitle', trans('{{ $resource->language->key('page_title') }}'))
            ->has('language.fields')
            ->has('{{ str($resource->resource)->lower()->plural() }}'));
})->group('{{ str($resource->resource)->lower() }}');

test('redirects guests to the login page', function (): void {
    $response = $this->get(route('{{ $resource->route->name() }}'));

    $response->assertRedirect(route('login'));
})->group('{{ str($resource->resource)->lower() }}');
