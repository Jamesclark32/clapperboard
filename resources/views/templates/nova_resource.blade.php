@php echo '<?php'.PHP_EOL @endphp

namespace {{ $resource->novaResource->namespace() }};

use App\Models\{{ $resource->novaResource->modelClassName() }};
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource as NovaResource;

class {{ $resource->novaResource->className() }} extends NovaResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<{{ $resource->novaResource->modelClassName() }}>
     */
    public static string $model = {{ $resource->novaResource->modelClassName() }}::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
@if(in_array('name', $resource->attributes->pluck('name')->toArray()))
        'name',
@endif
@if(in_array('email', $resource->attributes->pluck('name')->toArray()))
        'email',
@endif
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

@foreach($resource->attributes as $attribute)
@if($attribute->name === 'id')
@continue
@endif
@if(in_array($attribute->casts, ['boolean']))
            Boolean::make('{{ ucfirst(str_replace('_', ' ', $attribute->name)) }}', '{{ $attribute->name }}')
                ->sortable()
                ->filterable(),

@elseif(in_array($attribute->casts, ['datetime', 'timestamp']))
            DateTime::make('{{ ucfirst(str_replace('_', ' ', $attribute->name)) }}', '{{ $attribute->name }}')
                ->sortable()
                ->filterable(),

@else
            Text::make('{{ ucfirst(str_replace('_', ' ', $attribute->name)) }}', '{{ $attribute->name }}')
                ->sortable()
                ->rules('required', 'max:255'),

@endif
@endforeach
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }
}