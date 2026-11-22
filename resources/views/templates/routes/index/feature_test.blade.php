@php echo '<?php'.PHP_EOL @endphp

@if($resource->hasOwner())
use App\Models\{{ str($resource->resource)->studly()->singular() }};
@endif
use App\Models\User;
use Illuminate\Support\Facades\Route;

test('returns HTTP 200 response', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('{{ $resource->route->name() }}'{!! $resource->collectionRouteArguments() !!}));

    $response->assertOk();
})->group('{{ str($resource->resource)->lower() }}');

test('has expected route definition', function (): void {
    $route = Route::getRoutes()->getByName('{{ $resource->route->name() }}');

    expect($route->methods())->toContain('GET')
        ->and($route->gatherMiddleware())->toBe(['web'@foreach($resource->middleware as $middleware), {!! $resource->middlewareExport($middleware) !!}@endforeach]);
})->group('{{ str($resource->resource)->lower() }}');

test('returns expected inertia component and props', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('{{ $resource->route->name() }}'{!! $resource->collectionRouteArguments() !!}));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('{{ $resource->component->namespacedName() }}')
            ->where('pageTitle', trans('{{ $resource->language->key('page_title') }}'))
            ->has('language.fields')
            ->has('{{ str($resource->resource)->lower()->plural() }}'));
})->group('{{ str($resource->resource)->lower() }}');

test('redirects guests to the login page', function (): void {
    $response = $this->get(route('{{ $resource->route->name() }}'{!! $resource->collectionRouteArguments("'team'") !!}));

    $response->assertRedirect(route('login'));
})->group('{{ str($resource->resource)->lower() }}');

@if($resource->hasOwner())
@if($resource->isTeamOwned())
test('lists only the current team\'s records', function (): void {
@else
test('lists only the signed-in user\'s records', function (): void {
@endif
    $user = User::factory()->create();
    {{ str($resource->resource)->studly()->singular() }}::factory()->create({!! $resource->ownedFactoryState() !!});
    {{ str($resource->resource)->studly()->singular() }}::factory()->create();

    $response = $this->actingAs($user)->get(route('{{ $resource->route->name() }}'{!! $resource->collectionRouteArguments() !!}));

    $response->assertOk()
        ->assertInertia(fn ($page) => $page->has('{{ str($resource->resource)->lower()->plural() }}.data', 1));
})->group('{{ str($resource->resource)->lower() }}');
@endif
