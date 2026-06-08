@php echo '<?php'.PHP_EOL @endphp

use App\Models\{{ str($resource->resource)->studly()->singular() }};
use App\Models\User;

it('displays with no errors', function (): void {
    $user = User::factory()->create();
    ${{ str($resource->resource)->camel()->singular() }} = {{ str($resource->resource)->studly()->singular() }}::factory()->create();

    $this->actingAs($user);
    $page = visit(route('{{ $resource->route->name() }}', ['{{ str($resource->resource)->camel()->singular() }}' => ${{ str($resource->resource)->camel()->singular() }}]));

    $page->assertNoJavascriptErrors()
        ->assertSee(trans('{{ $resource->language->key('page_titles.show') }}'))
        ->assertDontSee('A canary');
})->group('{{ str($resource->resource)->lower() }}');

it('displays template elements', function (): void {
    $user = User::factory()->create();
    ${{ str($resource->resource)->camel()->singular() }} = {{ str($resource->resource)->studly()->singular() }}::factory()->create();

    $this->actingAs($user);
    $page = visit(route('{{ $resource->route->name() }}', ['{{ str($resource->resource)->camel()->singular() }}' => ${{ str($resource->resource)->camel()->singular() }}]));

    $page
        ->assertSee(trans('{{ $resource->language->key('messages.greeting') }}', ['name' => $user->name]))
        ->assertPresent('nav[aria-label="breadcrumb"]')
        ->assertPresent('[data-slot="sidebar"]');
})->group('{{ str($resource->resource)->lower() }}');
