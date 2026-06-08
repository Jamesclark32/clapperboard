@php echo '<?php'.PHP_EOL @endphp

use App\Models\User;

it('displays with no errors', function (): void {
    // Arrange
    $user = User::factory()->create();

    // Act
    $this->actingAs($user);
    $page = visit(route('{{ $resource->route->name() }}'));

    // Assert
    $page->assertNoJavascriptErrors()
        ->assertSee('{{ $resource->component->pageTitle() }}')
        ->assertDontSee('A canary');
})->group('{{ str($resource->resource)->lower() }}');

it('displays template elements', function (): void {
    // Arrange
    $user = User::factory()->create();

    // Act
    $this->actingAs($user);
    $page = visit(route('{{ $resource->route->name() }}'));

    // Assert
    $page
        ->assertSee($user->name)
        ->assertPresent('nav[aria-label="breadcrumb"]')
        ->assertPresent('[data-slot="sidebar"]');
})->group('{{ str($resource->resource)->lower() }}');
