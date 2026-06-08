@php echo '<?php'.PHP_EOL @endphp

use App\Models\User;

test('returns HTTP 200 response', function (): void {
    // Arrange
    $user = User::factory()->create();
@if(in_array($resource->verb, ['show', 'edit', 'update', 'destroy']))
    ${{ str($resource->resource)->singular()->lower() }} = {{ str($resource->resource)->singular()->studly() }}::factory()->create();
@endif
@if(in_array($resource->verb, ['store', 'update']))
    ${{ str($resource->resource)->singular()->lower() }}Transient = {{ str($resource->resource)->singular()->studly() }}::factory()->make();
@endif

    // Act
@if(in_array($resource->verb, ['index', 'create']))
    $response = $this->actingAs($user)->get(route('{{ $resource->route->name() }}'));
@elseif(in_array($resource->verb, ['show', 'edit']))
    $response = $this->actingAs($user)->get(route('{{ $resource->route->name() }}', ['{{ str($resource->resource)->singular()->lower() }}' => ${{ str($resource->resource)->singular()->lower() }}]));
@elseif($resource->verb === 'store')
    $response = $this->actingAs($user)->post(route('{{ $resource->route->name() }}'), ['{{ str($resource->resource)->singular()->lower() }}' => ${{str($resource->resource)->singular()->lower() }}Transient]);
@elseif($resource->verb === 'update')
    $response = $this->actingAs($user)->put(route('{{ $resource->route->name() }}', ['{{ str($resource->resource)->singular()->lower() }}' => ${{str($resource->resource)->singular()->lower() }}]), ['{{ str($resource->resource)->singular()->lower() }}' => ${{str($resource->resource)->singular()->lower() }}Transient]);
@elseif($resource->verb === 'destroy')
    $response = $this->actingAs($user)->delete(route('{{ $resource->route->name() }}', ['{{ str($resource->resource)->singular()->lower() }}' => ${{ str($resource->resource)->singular()->lower() }}]));
@endif

    // Assert
    $response->assertOk();
})->group('{{ str($resource->resource)->lower() }}');

test('has expected route definition', function (): void {
    // Arrange
    $route = Route::getRoutes()->getByName('{{ $resource->route->name() }}');

    // Act

    // Assert
    expect($route->methods())->toContain('{{ str($resource->route->httpVerb())->upper() }}')
        ->and($route->gatherMiddleware())->toBe(['web', 'auth', 'verified']);
})->group('{{ str($resource->resource)->lower() }}');

test('returns expected inertia component and props', function (): void {
    // Arrange
    $user = User::factory()->create();
@if(in_array($resource->verb, ['show', 'edit', 'update', 'destroy']))
    ${{ str($resource->resource)->singular()->lower() }} = {{ str($resource->resource)->singular()->studly() }}::factory()->create();
@endif
@if(in_array($resource->verb, ['store', 'update']))
    ${{ str($resource->resource)->singular()->lower() }}Transient = {{ str($resource->resource)->singular()->studly() }}::factory()->make();
@endif

    // Act
@if(in_array($resource->verb, ['index', 'create']))
    $response = $this->actingAs($user)->get(route('{{ $resource->route->name() }}'));
@elseif(in_array($resource->verb, ['show', 'edit']))
    $response = $this->actingAs($user)->get(route('{{ $resource->route->name() }}', ['{{ str($resource->resource)->singular()->lower() }}' => ${{ str($resource->resource)->singular()->lower() }}]));
@elseif($resource->verb === 'store')
    $response = $this->actingAs($user)->post(route('{{ $resource->route->name() }}'), ['{{ str($resource->resource)->singular()->lower() }}' => ${{str($resource->resource)->singular()->lower() }}Transient]);
@elseif($resource->verb === 'update')
    $response = $this->actingAs($user)->put(route('{{ $resource->route->name() }}', ['{{ str($resource->resource)->singular()->lower() }}' => ${{str($resource->resource)->singular()->lower() }}]), ['{{ str($resource->resource)->singular()->lower() }}' => ${{str($resource->resource)->singular()->lower() }}Transient]);
@elseif($resource->verb === 'destroy')
    $response = $this->actingAs($user)->delete(route('{{ $resource->route->name() }}', ['{{ str($resource->resource)->singular()->lower() }}' => ${{ str($resource->resource)->singular()->lower() }}]));
@endif

    // Assert
    $response->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('{{ $resource->component->namespacedName() }}')
            ->where('pageTitle', '{{ $resource->component->pageTitle() }}')
            ->has('user', fn ($userProp) => $userProp
                ->count(1)
                ->where('name', $user->name)
                ->hasAll(['name'])
                ->missingAll(['password', 'email', 'remember_token', 'id'])));
})->group('{{ str($resource->resource)->lower() }}');

test('redirects guests to the login page', function (): void {
    // Arrange
@if(in_array($resource->verb, ['show', 'edit', 'update', 'destroy']))
    ${{ str($resource->resource)->singular()->lower() }} = {{ str($resource->resource)->singular()->studly() }}::factory()->create();
@endif
@if(in_array($resource->verb, ['store', 'update']))
    ${{ str($resource->resource)->singular()->lower() }}Transient = {{ str($resource->resource)->singular()->studly() }}::factory()->make();
@endif

    // Act
@if(in_array($resource->verb, ['index', 'create']))
    $response = $this->get(route('{{ $resource->route->name() }}'));
@elseif(in_array($resource->verb, ['show', 'edit']))
    $response = $this->get(route('{{ $resource->route->name() }}', ['{{ str($resource->resource)->singular()->lower() }}' => ${{ str($resource->resource)->singular()->singular()->lower() }}->id]));
@elseif($resource->verb === 'store')
    $response = $this->post(route('{{ $resource->route->name() }}'), ['{{ str($resource->resource)->singular()->lower() }}' => ${{ str($resource->resource)->singular()->singular()->lower() }}Transient]);
@elseif($resource->verb === 'update')
    $response = $this->put(route('{{ $resource->route->name() }}', ['{{ str($resource->resource)->singular()->lower() }}' => ${{ str($resource->resource)->singular()->singular()->lower() }}->id]), ['{{ str($resource->resource)->singular()->lower() }}' => ${{ str($resource->resource)->singular()->lower() }}Transient]);
@elseif($resource->verb === 'destroy')
    $response = $this->delete(route('{{ $resource->route->name() }}', ['{{ str($resource->resource)->singular()->lower() }}' => ${{ str($resource->resource)->singular()->singular()->lower() }}->id]));
@endif

    // Assert
    $response->assertRedirect(route('login'));
})->group('{{ str($resource->resource)->lower() }}');

test('redirects unverified users to the verification page', function (): void {
    // Arrange
    $user = User::factory()->unverified()->create();

@if(in_array($resource->verb, ['show', 'edit', 'update', 'destroy']))
    ${{ str($resource->resource)->singular()->lower() }} = {{ str($resource->resource)->singular()->studly() }}::factory()->create();
@endif
@if(in_array($resource->verb, ['store', 'update']))
    ${{ str($resource->resource)->singular()->lower() }}Transient = {{ str($resource->resource)->singular()->studly() }}::factory()->make();
@endif

    // Act
@if(in_array($resource->verb, ['index', 'create']))
    $response = $this->actingAs($user)->get(route('{{ $resource->route->name() }}'));
@elseif(in_array($resource->verb, ['show', 'edit']))
    $response = $this->actingAs($user)-->get(route('{{ $resource->route->name() }}', ['{{ str($resource->resource)->singular()->lower() }}' => ${{ str($resource->resource)->singular()->singular()->lower() }}->id]));
@elseif($resource->verb === 'store')
    $response = $this->actingAs($user)-->post(route('{{ $resource->route->name() }}'), ['{{ str($resource->resource)->singular()->lower() }}' => ${{ str($resource->resource)->singular()->singular()->lower() }}Transient]);
@elseif($resource->verb === 'update')
    $response = $this->actingAs($user)-->put(route('{{ $resource->route->name() }}', ['{{ str($resource->resource)->singular()->lower() }}' => ${{ str($resource->resource)->singular()->singular()->lower() }}->id]), ['{{ str($resource->resource)->singular()->lower() }}' => ${{ str($resource->resource)->singular()->lower() }}Transient]);
@elseif($resource->verb === 'destroy')
    $response = $this->actingAs($user)-->delete(route('{{ $resource->route->name() }}', ['{{ str($resource->resource)->singular()->lower() }}' => ${{ str($resource->resource)->singular()->singular()->lower() }}->id]));
@endif

    // Assert
    $response->assertRedirect(route('verification.notice'));
})->group('{{ str($resource->resource)->lower() }}');

test('uses minimal database queries', function (): void {
    // Arrange
    $user = User::factory()->create();
    DB::enableQueryLog();
@if(in_array($resource->verb, ['show', 'edit', 'update', 'destroy']))
    ${{ str($resource->resource)->singular()->lower() }} = {{ str($resource->resource)->singular()->studly() }}::factory()->create();
@endif
@if(in_array($resource->verb, ['store', 'update']))
    ${{ str($resource->resource)->singular()->lower() }}Transient = {{ str($resource->resource)->singular()->studly() }}::factory()->make();
@endif

    // Act
@if(in_array($resource->verb, ['index', 'create']))
    $response = $this->actingAs($user)->get(route('{{ $resource->route->name() }}'));
@elseif(in_array($resource->verb, ['show', 'edit']))
    $response = $this->actingAs($user)->get(route('{{ $resource->route->name() }}', ['{{ str($resource->resource)->singular()->lower() }}' => ${{ str($resource->resource)->singular()->lower() }}]));
@elseif($resource->verb === 'store')
    $response = $this->actingAs($user)->post(route('{{ $resource->route->name() }}'), ['{{ str($resource->resource)->singular()->lower() }}' => ${{str($resource->resource)->singular()->lower() }}Transient]);
@elseif($resource->verb === 'update')
    $response = $this->actingAs($user)->put(route('{{ $resource->route->name() }}', ['{{ str($resource->resource)->singular()->lower() }}' => ${{str($resource->resource)->singular()->lower() }}]), ['{{ str($resource->resource)->singular()->lower() }}' => ${{str($resource->resource)->singular()->lower() }}Transient]);
@elseif($resource->verb === 'destroy')
    $response = $this->actingAs($user)->delete(route('{{ $resource->route->name() }}', ['{{ str($resource->resource)->singular()->lower() }}' => ${{ str($resource->resource)->singular()->lower() }}]));
@endif

    // Assert
    $response->assertOk();

    expect(count(DB::getQueryLog()))->toBeLessThanOrEqual(5);
})->group('{{ str($resource->resource)->lower() }}');

@if($resource->verb === 'store')
test('stores data', function (): void {
    // Arrange
    $user = User::factory()->create();
    ${{ str($resource->resource)->singular()->lower() }} = {{ str($resource->resource)->singular()->studly() }}::factory()->make();

    // Act
        $response = $this->actingAs($user)->post(route('{{ $resource->route->name() }}'), ['{{ str($resource->resource)->singular()->lower() }}' => ${{ str($resource->resource)->singular()->lower()}}]);

    // Assert
    $response->assertStatus(200)
    ->assertDatabaseCount('{{ str($resource->resource)->lower() }}', 1)
    ->assertDatabaseHas('{{ str($resource->resource)->lower() }}',
    [
     // @TODO: source field names. This will match data needs for migration, model, factory, form requests
    ]);
})->group('{{ str($resource->resource)->lower() }}');
@endif

@if($resource->verb === 'update')
test('updates data', function (): void {
    // Arrange
    $user = User::factory()->create();
    ${{ str($resource->resource)->singular()->lower() }} = {{ str($resource->resource)->singular()->studly() }}::factory()->create();
    ${{ str($resource->resource)->singular()->lower() }}Transient = {{ str($resource->resource)->singular()->studly() }}::factory()->make();

    // Act
    $response = $this->actingAs($user)->put(route('{{ $resource->route->name() }}', ['{{ str($resource->resource)->singular()->lower() }}' => ${{ str($resource->resource)->singular()->lower() }}->id]), ['{{ str($resource->resource)->lower() }}' => ${{ str($resource->resource)->singular()->singular()->lower() }}Transient]);

    // Assert
    $response->assertStatus(200)
    ->assertDatabaseCount('{{ str($resource->resource)->lower() }}', 1)
    ->assertDatabaseHas('{{ str($resource->resource)->lower() }}',
    [
    // @TODO: source field names. This will match data needs for migration, model, factory, form requests
    ]);
})->group('{{ str($resource->resource)->lower() }}');
@endif

@if($resource->verb === 'destroy')
test('destroys data', function (): void {
    // Arrange
    $user = User::factory()->create();
    ${{ str($resource->resource)->singular()->lower() }} = {{ str($resource->resource)->singular()->studly() }}::factory()->create();

    // Act
    $response = $this->actingAs($user)->delete(route('{{ $resource->route->name() }}', ['{{ str($resource->resource)->singular()->lower() }}' => ${{ str($resource->resource)->singular()->lower() }}->id]));

    // Assert
    $response->assertStatus(200)
    ->assertDatabaseCount('{{ str($resource->resource)->lower() }}', 0)
    ->assertDatabaseMissing('{{ str($resource->resource)->lower() }}',
    [
    // @TODO: source field names. This will match data needs for migration, model, factory, form requests
    ]);
})->group('{{ str($resource->resource)->lower() }}');
@endif
